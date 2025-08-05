<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionRequest;
use App\Models\Contacto;
use App\Models\MetodoPago;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TransaccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaccion::with(['contacto', 'metodoPago']);

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('contacto_id')) {
            $query->where('contacto_id', $request->contacto_id);
        }

        if ($request->filled('metodo_pago_id')) {
            $query->where('metodo_pago_id', $request->metodo_pago_id);
        }

        if ($request->filled('referencia_tipo')) {
            $query->where('referencia_tipo', $request->referencia_tipo);
        }

        if ($request->filled('factura_tipo')) {
            $query->where('factura_tipo', $request->factura_tipo);
        }

        // Búsqueda general
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('folio', 'like', "%{$search}%")
                    ->orWhere('referencia_nombre', 'like', "%{$search}%")
                    ->orWhere('factura_numero', 'like', "%{$search}%")
                    ->orWhere('referencia_pago', 'like', "%{$search}%")
                    ->orWhere('observaciones', 'like', "%{$search}%")
                    ->orWhereHas('contacto', function ($q) use ($search) {
                        $q->where('nombre', 'like', "%{$search}%");
                    });
            });
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'fecha');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSorts = ['folio', 'tipo', 'fecha', 'total', 'referencia_nombre', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $transacciones = $query->paginate(15)->appends($request->query());

        // Estadísticas para el dashboard
        $stats = $this->getStats();

        return view('transacciones.index', compact('transacciones', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contactos = Contacto::activos()->orderBy('nombre')->pluck('nombre', 'id');
        $metodosPago = MetodoPago::orderBy('nombre')->pluck('nombre', 'id');

        return view('transacciones.create', compact('contactos', 'metodosPago'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransaccionRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Procesar archivos de factura si existen
            if ($request->hasFile('factura_archivos')) {
                $archivos = [];
                foreach ($request->file('factura_archivos') as $file) {
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('transacciones/facturas', $filename, 'public');

                    $archivos[] = [
                        'nombre_original' => $file->getClientOriginalName(),
                        'nombre_archivo' => $filename,
                        'ruta' => $path,
                        'tamaño' => $file->getSize(),
                        'tipo_mime' => $file->getMimeType(),
                        'subido_en' => now()->toISOString(),
                    ];
                }
                $data['factura_archivos'] = $archivos;
            }

            // Crear la transacción
            $transaccion = Transaccion::create($data);

            DB::commit();

            return redirect()
                ->route('transacciones.index')
                ->with('success', 'Transacción creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear la transacción: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaccion $transaccione)
    {
        $transaccione->load(['contacto', 'metodoPago']);

        return view('transacciones.show', compact('transaccione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaccion $transaccione)
    {
        $contactos = Contacto::activos()->orderBy('nombre')->pluck('nombre', 'id');
        $metodosPago = MetodoPago::orderBy('nombre')->pluck('nombre', 'id');

        return view('transacciones.edit', compact('transaccione', 'contactos', 'metodosPago'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransaccionRequest $request, Transaccion $transaccione)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Procesar nuevos archivos de factura si existen
            if ($request->hasFile('factura_archivos')) {
                $archivosExistentes = $transaccione->factura_archivos ?? [];
                $nuevosArchivos = [];

                foreach ($request->file('factura_archivos') as $file) {
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('transacciones/facturas', $filename, 'public');

                    $nuevosArchivos[] = [
                        'nombre_original' => $file->getClientOriginalName(),
                        'nombre_archivo' => $filename,
                        'ruta' => $path,
                        'tamaño' => $file->getSize(),
                        'tipo_mime' => $file->getMimeType(),
                        'subido_en' => now()->toISOString(),
                    ];
                }

                // Combinar archivos existentes con nuevos
                $data['factura_archivos'] = array_merge($archivosExistentes, $nuevosArchivos);
            }

            // Actualizar la transacción
            $transaccione->update($data);

            DB::commit();

            return redirect()
                ->route('transacciones.index')
                ->with('success', 'Transacción actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar la transacción: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaccion $transaccione)
    {
        try {
            // Eliminar archivos físicos
            if ($transaccione->factura_archivos) {
                foreach ($transaccione->factura_archivos as $archivo) {
                    if (isset($archivo['ruta'])) {
                        Storage::disk('public')->delete($archivo['ruta']);
                    }
                }
            }

            $transaccione->delete();

            return redirect()
                ->route('transacciones.index')
                ->with('success', 'Transacción eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Error al eliminar la transacción: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar un archivo específico de una transacción
     */
    public function eliminarArchivo(Transaccion $transaccione, $archivoIndex)
    {
        try {
            $archivos = $transaccione->factura_archivos ?? [];

            if (isset($archivos[$archivoIndex])) {
                // Eliminar archivo físico
                if (isset($archivos[$archivoIndex]['ruta'])) {
                    Storage::disk('public')->delete($archivos[$archivoIndex]['ruta']);
                }

                // Quitar archivo del array
                unset($archivos[$archivoIndex]);

                // Reindexar array
                $archivos = array_values($archivos);

                // Actualizar transacción
                $transaccione->update(['factura_archivos' => $archivos]);

                return response()->json(['success' => true, 'message' => 'Archivo eliminado exitosamente.']);
            }

            return response()->json(['success' => false, 'message' => 'Archivo no encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar archivo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    private function getStats()
    {
        $hoy = now()->format('Y-m-d');
        $inicioMes = now()->startOfMonth()->format('Y-m-d');
        $finMes = now()->endOfMonth()->format('Y-m-d');

        return [
            // Totales del día
            'ingresos_hoy' => Transaccion::where('tipo', 'ingreso')
                ->whereDate('fecha', $hoy)
                ->sum('total'),

            'egresos_hoy' => Transaccion::where('tipo', 'egreso')
                ->whereDate('fecha', $hoy)
                ->sum('total'),

            // Totales del mes
            'ingresos_mes' => Transaccion::where('tipo', 'ingreso')
                ->whereBetween('fecha', [$inicioMes, $finMes])
                ->sum('total'),

            'egresos_mes' => Transaccion::where('tipo', 'egreso')
                ->whereBetween('fecha', [$inicioMes, $finMes])
                ->sum('total'),

            // Contadores
            'total_transacciones' => Transaccion::count(),
            'transacciones_mes' => Transaccion::whereBetween('fecha', [$inicioMes, $finMes])->count(),

            // Métodos de pago más usados
            'metodos_populares' => Transaccion::select('metodo_pago_id', DB::raw('count(*) as total'))
                ->with('metodoPago')
                ->groupBy('metodo_pago_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),

            // Referencias más comunes
            'referencias_populares' => Transaccion::select('referencia_tipo', DB::raw('count(*) as total'))
                ->groupBy('referencia_tipo')
                ->orderByDesc('total')
                ->get(),
        ];
    }

    /**
     * Exportar transacciones (para futuras implementaciones)
     */
    public function export(Request $request)
    {
        // TODO: Implementar exportación a Excel/PDF
        return back()->with('info', 'Funcionalidad de exportación en desarrollo.');
    }
}

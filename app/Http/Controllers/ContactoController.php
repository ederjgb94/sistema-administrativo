<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactoRequest;
use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Contacto::query();

        // Filtro por tipo
        if ($request->filled('tipo') && $request->tipo !== 'todos') {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por estado (activo/inactivo)
        if ($request->filled('estado')) {
            $activo = $request->estado === 'activo';
            $query->where('activo', $activo);
        }

        // Búsqueda por nombre, email o teléfono
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%")
                    ->orWhere('rfc', 'like', "%{$search}%");
            });
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'nombre');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $contactos = $query->paginate(15)->withQueryString();

        // Estadísticas
        $stats = [
            'total' => Contacto::count(),
            'clientes' => Contacto::clientes()->count(),
            'proveedores' => Contacto::proveedores()->count(),
            'activos' => Contacto::activos()->count(),
        ];

        return view('contactos.index', compact('contactos', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('contactos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactoRequest $request): RedirectResponse
    {
        $contacto = Contacto::create($request->validated());

        return redirect()
            ->route('contactos.show', $contacto)
            ->with('success', 'Contacto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contacto $contacto): View
    {
        $contacto->load(['transacciones' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('contactos.show', compact('contacto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contacto $contacto): View
    {
        return view('contactos.edit', compact('contacto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactoRequest $request, Contacto $contacto): RedirectResponse
    {
        $contacto->update($request->validated());

        return redirect()
            ->route('contactos.show', $contacto)
            ->with('success', 'Contacto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contacto $contacto): RedirectResponse
    {
        $contacto->delete(); // Soft delete

        return redirect()
            ->route('contactos.index')
            ->with('success', 'Contacto eliminado exitosamente.');
    }

    /**
     * Restore a soft deleted contact.
     */
    public function restore(int $id): RedirectResponse
    {
        $contacto = Contacto::withTrashed()->findOrFail($id);
        $contacto->restore();

        return redirect()
            ->route('contactos.show', $contacto)
            ->with('success', 'Contacto restaurado exitosamente.');
    }

    /**
     * Toggle the active status of a contact.
     */
    public function toggleStatus(Contacto $contacto): RedirectResponse
    {
        $contacto->update(['activo' => !$contacto->activo]);

        $status = $contacto->activo ? 'activado' : 'desactivado';

        return redirect()
            ->back()
            ->with('success', "Contacto {$status} exitosamente.");
    }

    /**
     * API para buscar contactos (autocompletado)
     */
    public function buscarApi(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $contactos = Contacto::where('activo', true)
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('telefono', 'like', "%{$query}%")
                    ->orWhere('rfc', 'like', "%{$query}%");
            })
            ->select('id', 'nombre', 'tipo', 'email', 'telefono')
            ->limit(10)
            ->get()
            ->map(function ($contacto) {
                return [
                    'id' => $contacto->id,
                    'nombre' => $contacto->nombre,
                    'tipo' => ucfirst($contacto->tipo),
                    'email' => $contacto->email,
                    'telefono' => $contacto->telefono,
                    'texto_completo' => $contacto->nombre . ' (' . ucfirst($contacto->tipo) . ')',
                    'descripcion' => $contacto->telefono . ' • ' . $contacto->email
                ];
            });

        return response()->json($contactos);
    }

    /**
     * Exportar contactos como PDF
     */
    public function export(Request $request)
    {
        try {
            Log::info('=== INICIO EXPORT PDF CONTACTOS ===', [
                'user_id' => Auth::id(),
                'filtros' => $request->all(),
                'url' => $request->fullUrl()
            ]);

            // Usamos el mismo query builder que para el index pero sin paginación
            $query = Contacto::query();

            // Filtro por tipo
            if ($request->filled('tipo') && $request->tipo !== 'todos') {
                Log::info('Aplicando filtro de tipo', ['tipo' => $request->tipo]);
                $query->where('tipo', $request->tipo);
            }

            // Filtro por estado (activo/inactivo)
            if ($request->filled('estado')) {
                $activo = $request->estado === 'activo';
                Log::info('Aplicando filtro de estado', ['activo' => $activo]);
                $query->where('activo', $activo);
            }

            // Búsqueda por nombre, email o teléfono
            if ($request->filled('search')) {
                $search = $request->search;
                Log::info('Aplicando filtro de búsqueda', ['search' => $search]);
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telefono', 'like', "%{$search}%")
                        ->orWhere('rfc', 'like', "%{$search}%");
                });
            }

            // Ordenamiento
            $sortBy = $request->get('sort_by', 'nombre');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortBy, $sortDirection);

            // Obtener todos los contactos (sin paginación para PDF)
            $contactos = $query->get();

            Log::info('Contactos obtenidos para PDF', [
                'count' => $contactos->count(),
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Estadísticas para el PDF
            $stats = [
                'total' => $contactos->count(),
                'clientes' => $contactos->where('tipo', 'cliente')->count(),
                'proveedores' => $contactos->where('tipo', 'proveedor')->count(),
                'ambos' => $contactos->where('tipo', 'ambos')->count(),
                'activos' => $contactos->where('activo', true)->count(),
                'inactivos' => $contactos->where('activo', false)->count(),
            ];

            // Preparar datos para la vista
            $data = [
                'contactos' => $contactos,
                'stats' => $stats,
                'filtros' => $request->all(),
                'fecha_generacion' => now()->format('d/m/Y H:i'),
            ];

            Log::info('Generando PDF de contactos...');

            // Generar el PDF con la vista
            $pdf = Pdf::loadView('contactos.export-pdf', $data);

            // Configuración básica
            $pdf->setPaper('a4');

            // Configurar opciones de DomPDF
            $dompdf = $pdf->getDomPDF();
            $dompdf->set_option('isPhpEnabled', true);
            $dompdf->set_option('enable_javascript', true);
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->set_option('isHtml5ParserEnabled', true);

            Log::info('PDF de contactos generado exitosamente');

            $filename = 'contactos_' . now()->format('Y-m-d_H-i-s') . '.pdf';

            Log::info('Enviando descarga', ['filename' => $filename]);

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('=== ERROR EN EXPORT PDF CONTACTOS ===', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => substr($e->getTraceAsString(), 0, 2000)
            ]);

            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de contactos
    Route::resource('contactos', ContactoController::class);
    Route::patch('/contactos/{contacto}/toggle-status', [ContactoController::class, 'toggleStatus'])->name('contactos.toggle-status');
    Route::patch('/contactos/{id}/restore', [ContactoController::class, 'restore'])->name('contactos.restore');
    Route::get('contactos-export', [ContactoController::class, 'export'])->name('contactos.export');

    // Rutas de transacciones
    Route::resource('transacciones', TransaccionController::class);
    Route::delete('transacciones/{transaccione}/archivo/{index}', [TransaccionController::class, 'eliminarArchivo'])->name('transacciones.eliminar-archivo');
    Route::get('transacciones-export', [TransaccionController::class, 'export'])->name('transacciones.export');

    // Rutas de reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reporte-diario', [ReporteController::class, 'reporteDiario'])->name('reporte.diario');
    Route::get('reporte-periodo', [ReporteController::class, 'reportePeriodo'])->name('reporte.periodo');
    Route::get('reporte-contactos', [ReporteController::class, 'reporteContactos'])->name('reporte.contactos');
    Route::get('reporte-metodos-pago', [ReporteController::class, 'reporteMetodosPago'])->name('reporte.metodos-pago');
    Route::get('reporte-mensual', [ReporteController::class, 'reporteMensual'])->name('reporte.mensual');
    Route::get('reporte-contacto', [ReporteController::class, 'reporteContacto'])->name('reporte.contacto');

    // API para autocompletado
    Route::get('api/contactos/buscar', [ContactoController::class, 'buscarApi'])->name('api.contactos.buscar');
});

require __DIR__ . '/auth.php';

// Ruta temporal para testing PDF sin autenticación
Route::get('/test-transacciones-export', [App\Http\Controllers\TransaccionController::class, 'export'])->name('test.transacciones.export');

// Ruta de debug temporal para PDF
Route::get('/debug-pdf', function () {
    try {
        \Illuminate\Support\Facades\Log::info('Debug PDF: Iniciando...');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('<h1>Test Debug PDF</h1><p>Timestamp: ' . now() . '</p>');

        \Illuminate\Support\Facades\Log::info('Debug PDF: PDF creado exitosamente');

        return $pdf->download('debug-test.pdf');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Debug PDF Error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()]);
    }
});

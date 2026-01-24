<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentDayLimitController;
use App\Http\Controllers\ClientController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Rutas por roles
    |--------------------------------------------------------------------------
    */

    // Solo admin
    Route::middleware('role:admin')->group(function () {
        Route::view('/admin', 'admin.index')->name('admin.index');
        Route::resource('usuarios', UserController::class);
    });

    // Vendedor y admin
    Route::middleware('role:vendedor|admin')->group(function () {
        Route::view('/ventas', 'ventas.index')->name('ventas.index');
    });

    // Mecánico y admin
    Route::middleware('role:mecanico|admin')->group(function () {
        Route::view('/taller', 'taller.index')->name('taller.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Citas
    |--------------------------------------------------------------------------
    */
    Route::prefix('citas')->name('citas.')->group(function () {

        // Calendario
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/events', [AppointmentController::class, 'events'])->name('events');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');

        // Estado de días (para pintar colores/bloqueos en el calendario)
        Route::get('/days-status', [AppointmentController::class, 'daysStatus'])->name('days.status');

        // Límite por día (DEBE IR ANTES que /{appointment})
        Route::get('/limite', [AppointmentDayLimitController::class, 'show'])->name('limite.show');
        Route::post('/limite', [AppointmentDayLimitController::class, 'upsert'])->name('limite.upsert');
        Route::delete('/limite', [AppointmentDayLimitController::class, 'destroy'])->name('limite.destroy');

        // Update cita (solo números)
        Route::put('/{appointment}', [AppointmentController::class, 'update'])
            ->whereNumber('appointment')
            ->name('update');

        // Toggle attended (solo números)
        Route::patch('/{appointment}/attended', [AppointmentController::class, 'toggleAttended'])
            ->whereNumber('appointment')
            ->name('attended');

        // Eliminar cita (solo números)
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])
            ->whereNumber('appointment')
            ->name('destroy');
    });

     /*
    |--------------------------------------------------------------------------
    |Clientes
    |--------------------------------------------------------------------------
    */
    Route::resource('clients', ClientController::class);

// Extra PRO: activar/inactivar
Route::patch('clients/{client}/toggle-status', [ClientController::class, 'toggleStatus'])
    ->name('clients.toggle-status');
});

require __DIR__ . '/auth.php';

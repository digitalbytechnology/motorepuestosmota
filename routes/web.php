<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentDayLimitController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\LaborController;

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
| Rutas protegidas
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
    | SOLO ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::view('/admin', 'admin.index')->name('admin.index');
        Route::resource('usuarios', UserController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN | VENDEDOR | MECÁNICO
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin|vendedor|mecanico')->group(function () {

        // Ventas
        Route::view('/ventas', 'ventas.index')->name('ventas.index');

        // Clientes
        Route::resource('clientes', ClientController::class)
            ->parameters(['clientes' => 'client']);

        Route::patch('clientes/{client}/toggle-status', [ClientController::class, 'toggleStatus'])
            ->name('clientes.toggle-status');

        // Vehículos
        Route::resource('vehiculos', VehicleController::class)
            ->parameters(['vehiculos' => 'vehiculo']);

        // Labores
        Route::resource('labors', LaborController::class)
            ->except('show');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN | MECÁNICO (Taller)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin|mecanico')->group(function () {
        Route::view('/taller', 'taller.index')->name('taller.index');
    });

    /*
    |--------------------------------------------------------------------------
    | CITAS
    |--------------------------------------------------------------------------
    */
    Route::prefix('citas')->name('citas.')->group(function () {

        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/events', [AppointmentController::class, 'events'])->name('events');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');

        Route::get('/days-status', [AppointmentController::class, 'daysStatus'])->name('days.status');

        Route::get('/limite', [AppointmentDayLimitController::class, 'show'])->name('limite.show');
        Route::post('/limite', [AppointmentDayLimitController::class, 'upsert'])->name('limite.upsert');
        Route::delete('/limite', [AppointmentDayLimitController::class, 'destroy'])->name('limite.destroy');

        Route::put('/{appointment}', [AppointmentController::class, 'update'])
            ->whereNumber('appointment')
            ->name('update');

        Route::patch('/{appointment}/attended', [AppointmentController::class, 'toggleAttended'])
            ->whereNumber('appointment')
            ->name('attended');

        Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])
            ->whereNumber('appointment')
            ->name('destroy');
    });
});

require __DIR__ . '/auth.php';

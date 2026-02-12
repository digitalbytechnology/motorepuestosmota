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

use App\Http\Controllers\PartCategoryController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\InventoryMovementController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderInspectionController;

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
    | Solo admin
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::view('/admin', 'admin.index')->name('admin.index');
        Route::resource('usuarios', UserController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Vendedor o admin
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:vendedor|admin')->group(function () {

        Route::view('/ventas', 'ventas.index')->name('ventas.index');

        // Clientes
        Route::resource('clientes', ClientController::class)
            ->parameters(['clientes' => 'client']);

        Route::patch('clientes/{client}/toggle-status', [ClientController::class, 'toggleStatus'])
            ->name('clientes.toggle-status');

        // Vehículos
        Route::resource('vehiculos', VehicleController::class)
            ->parameters(['vehiculos' => 'vehiculo']);

        // Mano de obra
        Route::resource('labors', LaborController::class)->except('show');

        // Categorías repuestos
        Route::resource('categories', PartCategoryController::class)
            ->parameters(['categories' => 'category']);

        // Repuestos / Parts
        Route::resource('parts', PartController::class)->except('show');

        // Movimientos inventario por repuesto
        Route::prefix('parts/{part}/inventory')->name('inventory.')->group(function () {
            Route::get('/create', [InventoryMovementController::class, 'create'])->name('create');
            Route::post('/', [InventoryMovementController::class, 'store'])->name('store');
            Route::get('/kardex', [InventoryMovementController::class, 'kardex'])->name('kardex');
        });

        // Órdenes 
        
        Route::resource('orders', OrderController::class)->except('show');
    });

    /*
    |--------------------------------------------------------------------------
    | Mecánico o admin
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:mecanico|admin')->group(function () {
        Route::view('/taller', 'taller.index')->name('taller.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Inspección de Orden (vendedor + mecanico + admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:vendedor|mecanico|admin')->group(function () {

        Route::prefix('orders/{order}/inspection')
            ->name('orders.inspection.')
            ->group(function () {

                // Edit / Update inspección
                Route::get('/', [OrderInspectionController::class, 'edit'])->name('edit');
                Route::put('/', [OrderInspectionController::class, 'update'])->name('update');

                // Fotos
                Route::post('/photos', [OrderInspectionController::class, 'storePhotos'])->name('photos.store');
                Route::delete('/photos/{photo}', [OrderInspectionController::class, 'destroyPhoto'])->name('photos.destroy');

                // Annotations JSON por foto
                Route::patch('/photos/{photo}/annotations', [OrderInspectionController::class, 'saveAnnotations'])->name('photos.annotations');

                // Firma
                Route::post('/signature', [OrderInspectionController::class, 'saveSignature'])->name('signature');
            });
    });

    /*
    |--------------------------------------------------------------------------
    | Citas
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
            ->whereNumber('appointment')->name('update');

        Route::patch('/{appointment}/attended', [AppointmentController::class, 'toggleAttended'])
            ->whereNumber('appointment')->name('attended');

        Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])
            ->whereNumber('appointment')->name('destroy');
    });
});

require __DIR__ . '/auth.php';

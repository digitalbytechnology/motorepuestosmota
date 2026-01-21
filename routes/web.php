<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

 
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

        // CRUD usuarios (solo admin)
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

});

require __DIR__ . '/auth.php';

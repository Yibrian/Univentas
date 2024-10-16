<?php

use App\Livewire\Controllers\BusquedaController;
use App\Livewire\Controllers\CategoriaController;
use App\Livewire\Controllers\DashboardController;
use App\Livewire\Controllers\ProductoController;
use App\Livewire\Controllers\VendedorController;
use App\Livewire\Controllers\VerProductoController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Controllers\UsersController;
use App\Http\Controllers\FileDownloadController;








Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::group(['middleware' => ['auth', 'role:admin']], function () {

    Route::get('users', UsersController::class)
        ->name('users');

    Route::get('/download-file/{path}', [FileDownloadController::class, 'downloadFile'])
        ->where('path', '.*')
        ->name('download.file');

    Route::get('categorias', CategoriaController::class)
        ->name('categorias');


});
Route::group(['middleware' => ['auth']], function () {
    Volt::route('mi-tienda', 'vendedor.form')
        ->name('mi.tienda');

    Route::get('dashboard', DashboardController::class)
        ->name('dashboard');
});

Route::group(['middleware' => ['auth', 'role:admin|cliente|vendedor']], function () {
    Route::get('producto/{id}', VerProductoController::class)
    ->name('producto');

    Route::get('/busqueda/{tipo}/{clave}', BusquedaController::class)->name('busqueda');

});
Route::group(['middleware' => ['auth', 'role:cliente']], function () {
});


Route::group(['middleware' => ['auth', 'role:vendedor']], function () {
    Route::get('vender', ProductoController::class)
        ->name('vender');
});







require __DIR__ . '/auth.php';

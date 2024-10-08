<?php

use App\Livewire\Controllers\VendedorController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Controllers\UsersController;
use App\Http\Controllers\FileDownloadController;




Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::group(['middleware' => ['auth', 'role:admin']], function () {

    Route::get('users', UsersController::class)
        ->name('users');
    Route::get('/download-file/{path}', [FileDownloadController::class, 'downloadFile'])
        ->where('path', '.*')
        ->name('download.file');


});
Route::group(['middleware' => ['auth']], function () {
    Volt::route('mi-tienda', 'vendedor.form')
        ->name('mi.tienda');
});


Route::group(['middleware' => ['auth', 'role:cliente']], function () {
});


Route::group(['middleware' => ['auth', 'role:vendedor']], function () {
    Route::get('vender', VendedorController::class)
        ->name('vender');
});







require __DIR__ . '/auth.php';

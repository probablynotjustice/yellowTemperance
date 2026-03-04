<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Landing');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::prefix('admin')->group(funtion (){

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
})    
require __DIR__.'/settings.php';

<?php

use App\Http\Controllers\AngkutanController;
use App\Http\Controllers\MerekController;
use App\Http\Controllers\PerushaanController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource("perusahaan", PerushaanController::class)->middleware(['auth', 'verified']);
Route::resource("merek", MerekController::class)->middleware(['auth', 'verified']);
Route::resource("angkutan", AngkutanController::class)->middleware(['auth', 'verified']);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

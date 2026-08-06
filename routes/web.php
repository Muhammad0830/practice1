<?php

use App\Http\Controllers\Practice\PracticeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::inertia('/login', 'auth/Login')->name('login');

Route::inertia('/home', 'Dashboard')->name('home');

Route::get('/practice', [PracticeController::class, 'get'])->name('practice');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__ . '/settings.php';

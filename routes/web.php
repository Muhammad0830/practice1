<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::inertia('/login', 'auth/Login')->name('login');

Route::inertia('/home', 'Dashboard')->name('home');

Route::post('/signup', [UserController::class, 'signup'])->name('signup');

Route::get('/users', [UserController::class, 'get'])->name('users');
Route::inertia('/users/create', 'users/Create')->name('users-create');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__ . '/settings.php';

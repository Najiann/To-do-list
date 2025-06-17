<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::get('/', function () {
    return view('welcome'); 
});

Route::get('/dashboard', [TodoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Todo
    Route::post('/todo', [TodoController::class, 'store']);
    Route::patch('/todo/{id}/done', [TodoController::class, 'markAsDone']);
    Route::delete('/todo/{id}', [TodoController::class, 'destroy']);
});

require __DIR__.'/auth.php';

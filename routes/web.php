<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects', [ProjectController::class, 'index'])->name('project.index');
    Route::prefix('project')->group(function () {
        Route::post('/', [ProjectController::class, 'store'])->name('project.store');
    });

    Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
    Route::prefix('task')->group(function () {
        Route::post('/', [TaskController::class, 'store'])->name('task.store');
        Route::put('/{id}', [TaskController::class, 'update'])->name('task.update');
        Route::delete('/{id}', [TaskController::class, 'delete'])->name('task.delete');
        Route::post('/reorder', [TaskController::class, 'reorder'])->name('task.reorder');
    });
});

require __DIR__ . '/auth.php';

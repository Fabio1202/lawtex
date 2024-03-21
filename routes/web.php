<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect to the projects index view
Route::get('/', function () {
    return redirect(route('projects.index'));
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::prefix('projects')->group(function () {
        Route::get('/', [App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
        //        Route::get('/create', [App\Http\Controllers\ProjectController::class, 'create'])->name('projects.create');
        Route::post('/', [App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
        Route::get('/{project}', [App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');
        //        Route::get('/{project}/edit', [App\Http\Controllers\ProjectController::class, 'edit'])->name('projects.edit');
        //        Route::patch('/{project}', [App\Http\Controllers\ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/{project}', [App\Http\Controllers\ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::patch('/{project}/rename', [App\Http\Controllers\ProjectController::class, 'rename'])->name('projects.rename');
    });
});

require __DIR__.'/auth.php';

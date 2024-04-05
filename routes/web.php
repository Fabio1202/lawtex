<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

        Route::get('/{project}/latex.tex', [App\Http\Controllers\ProjectController::class, 'latex'])->name('projects.latex');

        Route::post('/{project}/laws', [App\Http\Controllers\LawController::class, 'store'])->name('laws.store');
    });

    Route::prefix('users')->name('users.')->middleware(\App\Http\Middleware\IsAdminMiddleware::class)->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('show');
        Route::put('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('reset-password');
        Route::post('/create-activation-link', [App\Http\Controllers\UserController::class, 'createActivationLink'])->name('create-activation-link');
    });
});

Route::middleware('signed')->group(function () {
    Route::get('activate-account', [App\Http\Controllers\ActivateAccountController::class, 'index'])->name('activate-account.index');
    Route::post('activate-account', [App\Http\Controllers\ActivateAccountController::class, 'store'])->name('activate-account.store');
});

if (app()->environment('local')) {
    Route::get('test', function () {
        $law = \App\Models\Law::first();
        $law->url = 'https://www.gesetze-im-internet.de/bgb/__311.html#:~:text=§%20311%20Rechtsgeschäftliche%20und%20rechtsgeschäftsähnliche,das%20Gesetz%20ein%20anderes%20vorschreibt.';
        $parser = new \App\Parsers\Base\LawParser();
        $parsed = $parser->fullParse($law);
        dump($parsed);
        dump($parsed->toLatex());
        dump($law->project->toLatex());
        dd($parser->parseInformation($law->url));
    })->name('test');

    Route::get('activate-account/get-link', function () {
        return URL::signedRoute('activate-account.index', ['email' => 'test@test.com']);
    });
}

require __DIR__.'/auth.php';

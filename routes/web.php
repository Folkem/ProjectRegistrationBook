<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/update', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('projects', ProjectController::class)->except('show');

    Route::get('projects/export', [ProjectController::class, 'export'])->name('projects.export');
    Route::get('projects/upload', [ProjectController::class, 'upload'])->name('projects.upload');
    Route::post('projects/upload/preview', [ProjectController::class, 'uploadPreview'])
        ->name('projects.upload.preview');
    Route::post('projects/upload/store', [ProjectController::class, 'uploadStore'])
        ->name('projects.upload.store');

    Route::get('/', function() {
        return redirect('projects');
    });
});

<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PagePdfExportController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class);

    Route::resource('projects.pages', PageController::class)->scoped();
    Route::post('projects/{project}/pages/{page}/export', [PagePdfExportController::class, 'store'])
        ->scopeBindings()
        ->name('projects.pages.export.store');
    Route::get('projects/{project}/pages/{page}/export', [PagePdfExportController::class, 'show'])
        ->scopeBindings()
        ->name('projects.pages.export.show');
});

require __DIR__.'/settings.php';

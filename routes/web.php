<?php

use App\Http\Controllers\FileOrganizerController;
use Illuminate\Support\Facades\Route;
use function Pest\Laravel\post;

Route::prefix('/api/v1')->group(function () {
    Route::post('/organize', [FileOrganizerController::class, 'organizeJson'])->name('organize');
    Route::post('/organize-file', [FileOrganizerController::class, 'organizeFile'])->name('organize-file');
});

Route::get('/', function () {
    return view('home.index');
});

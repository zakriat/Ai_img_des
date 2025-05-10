<?php

use App\Http\Controllers\ImageDescriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/image-description', [ImageDescriptionController::class, 'index'])->name('image-description.index');
Route::post('/image-description/generate', [ImageDescriptionController::class, 'generateDescription'])->name('image-description.generate');

<?php

use App\Http\Controllers\imagedataController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\UploadedimageController;
use Illuminate\Support\Facades\Route;

// Public Route
Route::get('/', function () {
    return view('welcome');
});

// Route for login
Route::get('/login', [loginController::class, 'index'])->name('login');
Route::get('/create-user', [loginController::class, 'createUser'])->name('create.user');
Route::post('/store-create-user', [loginController::class, 'createUserStore'])->name('create.store');
Route::post('/login-check', [loginController::class, 'login'])->name('login.check');


// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/image-data', [imagedataController::class, 'index'])->name('image-data');
    Route::post('/store-images', [imagedataController::class, 'uploadImages'])->name('upload.images');
    Route::get('/uploaded-image', [UploadedimageController::class, 'showImages'])->name('images.show');
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');

});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/app', function () {
    return view('/app/login');
});

Route::get('/app/{any}', function () {
    return view('welcome');
});

Route::get('/check-email/{token}', [AuthController::class, 'verifyEmail']);

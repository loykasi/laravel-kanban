<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/check-email/{token}', [AuthController::class, 'verifyEmail']);

Route::view('/{any}', 'welcome')->where('any', '.*');
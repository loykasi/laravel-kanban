<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TaskController;

Route::controller(AuthController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

// Route::group(['middleware'=>['auth:sanctum']], function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('/logout', 'logout');
    });

    Route::controller(ProjectController::class)->group(function() {
        Route::post('/project', 'store');
        Route::put('/project', 'update');
        Route::get('/project', 'index');
        Route::post('/project/pin', 'pinProject');
        Route::get('/project/{slug}', 'getProject');
        Route::get('/count/project', 'countProject');
        Route::get('/pin/project', 'getPinnedProject');
        Route::get('/project/chart/{projectId}', 'getProjectChartData');
    });
    
    Route::controller(MemberController::class)->group(function() {
        Route::post('/member', 'store');
        Route::put('/member', 'update');
        Route::get('/member', 'index');
    });
    
    Route::controller(TaskController::class)->group(function() {
        Route::post('/task', 'createTask');
        Route::post('/task/set-status', 'setTaskStatus');
    });
// });

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\CardListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TaskController;

Route::controller(AuthController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/verify-email/{token}', 'verifyEmail');
});

// Route::group(['middleware'=>['auth:sanctum']], function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('/logout', 'logout');
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

    Route::controller(ProjectController::class)->group(function() {
        Route::get('/project', 'index');
        Route::get('/user/project', 'getUserProject');
        Route::post('/project', 'store');
        Route::put('/project', 'update');
        Route::post('/project/pin', 'pinProject');
        Route::get('/project/{slug}', 'getProject');
        Route::get('/count/project', 'countProject');
        Route::get('/pin/project', 'getPinnedProject');
        Route::get('/project/chart/{projectId}', 'getProjectChartData');
    });

    Route::controller(CardListController::class)->group(function() {
        Route::get('project/{projectId}/list', 'index');
        Route::post('project/{projectId}/list', 'store');
        Route::put('/list', 'update');
        Route::put('/list/reorder', 'reorder');
        Route::delete('/list', 'delete');
    });

    Route::controller(CardController::class)->group(function() {
        Route::get('/list/{listId}', 'index');
        Route::post('/list/{listId}/card', 'store');
        Route::put('/card', 'update');
        Route::put('/card/reorder', 'reorder');
        Route::delete('/card', 'delete');
    });
// });

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

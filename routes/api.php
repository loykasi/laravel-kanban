<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\CardListController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Broadcasting\BroadcastController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TaskController;

Route::controller(AuthController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/verify-email/{token}', 'verifyEmail');
});

Route::group(['middleware'=>['auth:sanctum']], function() {
    Route::post('/broadcasting/auth', [BroadcastController::class, 'authenticate']);

    Route::controller(AuthController::class)->group(function() {
        Route::post('/logout', 'logout');
    });
    
    Route::controller(MemberController::class)->group(function() {
        Route::get('/member/{id}', 'getProjectMember');
        Route::post('/member', 'store');
        Route::put('/member', 'update');
        Route::delete('/member', 'delete');
    });
    
    Route::controller(TaskController::class)->group(function() {
        Route::post('/task', 'createTask');
        Route::post('/task/set-status', 'setTaskStatus');
    });

    Route::controller(ProjectController::class)->group(function() {
        Route::get('/project', 'index');
        Route::get('/user/{userId}/project', 'getUserProject');
        Route::get('/user/{userId}/collab/project', 'getUserCollabProject');
        Route::get('/project/{projectId}', 'getProjectDetail');
        Route::post('/project', 'store');
        Route::put('/project', 'update');

        Route::post('/project/pin', 'pinProject');
        Route::get('/count/project', 'countProject');
        Route::get('/pin/project', 'getPinnedProject');
        Route::get('/project/chart/{projectId}', 'getProjectChartData');
    });

    Route::controller(CardListController::class)->group(function() {
        Route::get('/project/{projectId}/list', 'index');
        Route::post('/list', 'store');
        Route::put('/list/{listId}', 'update');
        Route::delete('/list/{listId}', 'delete');
    });

    Route::controller(CardController::class)->group(function() {
        // Route::get('/list/{listId}/card', 'index');
        Route::post('/card', 'store');
        Route::put('/card/{cardId}', 'update');
        Route::delete('/card/{cardId}', 'delete');
    });
});

Route::controller(UserController::class)->group(function() {
    Route::get('/user/{id}', 'index');
});

Route::controller(CommentController::class)->group(function() {
    Route::get('/card/{cardId}/comment', 'index');
    Route::post('/card/{cardId}/comment', 'store');
    Route::delete('/comment/{cardId}', 'delete');
});
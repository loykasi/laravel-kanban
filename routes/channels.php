<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

// Broadcast::routes(['middleware' => 'auth:sanctum']);
// Broadcast::routes(['prefix' => 'api', 'middleware' => 'api']);

Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return $user->id === $id;
});

Broadcast::channel('board.{userId}', function (User $user, $userId) {
    // return (int) $user->id === Project::find($projectId)->userId;
    return true;
});

Broadcast::channel('project.{id}', function (User $user, $id) {
    // return (int) $user->id === Project::find($projectId)->userId;
    return true;
});
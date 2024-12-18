<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(string $id, Request $request) {
        $user = User::find($id);

        if ($user === null) {
            return response([
                "message" => "Not found"
            ], 404);
        }

        return response([
            "email" => $user->email,
            "joined_at" => $user->created_at,
        ], 404);
    }
}

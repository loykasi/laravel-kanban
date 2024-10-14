<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\LogoutRequest;
use App\Http\Requests\User\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Str;
use App\Models\User;
use App\Events\NewUserCreated;

class AuthController extends Controller
{
    private $secretKey = "nqtJHFgpSNj3SUAKKDcIB51zZwqzT8/uMVRwShqD4L7Vzzt9y1uKPbkMge/WhKHOhYwcfeGTWWF5";

    public function register(RegisterRequest $request) {
        $fields = $request->validated();

        $user = User::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'isValidEmail' => User::IS_INVALID_EMAIL,
            'remember_token' => $this->generateRandomToken()
        ]);

        NewUserCreated::dispatch($user);

        return response([
            'user' => $user,
            'message' => 'user created'
            ], 200);
    }

    public function verifyEmail($token) {
        User::where('remember_token', $token)->update(['isValidEmail' => User::IS_VALID_EMAIL]);

        return redirect('/login');
    }

    public function login(LoginRequest $request) {
        $fields = $request->validated();

        $user = User::where('email', $fields['email'])->first();

        if (!is_null($user)) {
            if (intval($user->isValidEmail) !== User::IS_VALID_EMAIL) {
                NewUserCreated::dispatch($user);
                return response([
                    'message' => 'Verification email has been sent',
                    'isLoggedIn' => false,
                ], 422);
            }
        }

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Email or password invalid',
                'isLoggedIn' => false,
            ], 422);
        }

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln("<info>access</info>");

        $token = $user->createToken($this->secretKey)->plainTextToken;
        return response([
            'message' => 'user logged in',
            'isLoggedIn' => true,
            'user' => $user,
            'token' => $token
            ], 200);
    }

    public function logout(LogoutRequest $request) {
        $fields = $request->validated();

        DB::table('personal_access_tokens')
        ->where('tokenable_id', $fields['userId'])
        ->delete();

        return response([
            'message' => 'logout user'
        ], 200);
    }

    public function generateRandomToken() {
        $code = Str::random(10) . time();
        return $code;
    }
}

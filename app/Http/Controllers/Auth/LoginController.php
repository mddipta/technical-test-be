<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $req = $request->validated();

        if (!Auth::attempt($req)) {
            return $this->error('Unauthorized', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie(
            'auth_token',
            $token,
            60 * 24 * 7,
            '/',
            null,
            false,
            true,
            false,
            'Lax'
        );

        return $this->success(null, 'Login Successful')->cookie($cookie);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        // Clear the auth token cookie
        $cookie = cookie(
            'auth_token',
            null,
            -1,
            '/',
            null,
            false,
            true,
            false,
            'Lax'
        );

        return $this->success(null, 'Logged out successfully')->cookie($cookie);
    }

    public function register(RegisterRequest $request)
    {
        $req = $request->validated();

        $user = new User();
        $user->name = $req['name'];
        $user->email = $req['email'];
        $user->password = Hash::make($req['password']);
        $user->save();

        return $this->success(null, 'User created successfully');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // validate input
        $request->validate(rules: [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // find user
        $user = User::where(column: 'email', operator: $request->email)->first();

        // check credentials
        if (!$user || !Hash::check(value: $request->password, hashedValue: $user->password)) {
            return response()->json(data: [
                'message' => 'The provided credentials do not match our records.'
            ], status: 401);
        }

        // create token
        $token = $user->createToken(name: 'auth_token')->plainTextToken;

        // return response
        return response()->json(data: [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        // validate input
        $request->validate(rules: [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // create user
        $user = User::create(attributes: [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(value: $request->password),
        ]);

        // create token
        $token = $user->createToken(name: 'auth_token')->plainTextToken;

        // return response
        return response()->json(data: [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}

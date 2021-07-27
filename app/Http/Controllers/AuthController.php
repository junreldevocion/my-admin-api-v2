<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request) {
        
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || ! Hash::check($fields['password'], $user->password)) {
            throw ValidationException::withMessages([
                'message' => 'Username or password incorrect!'
            ]);
        }


        $token = $user->createToken('myapptoken')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function logout(Request $request) {

        auth()->user()->tokens()->delete();

        return response(['message' => 'Logged out']);

    } 

    public function updatePassword(Request $request)
    {
        $fields = $request->validate([
            'password_old' => 'required|string',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required|string'
        ]);

        $user = $request->user();

        if(!$user || !Hash::check($fields['password_old'], $user->password)) {
            
            throw ValidationException::withMessages([
                'message' => 'Invalid old password!'
            ]);

        }

        $user->update([
            'password' => bcrypt($fields['password'])
        ]);
        
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }
}

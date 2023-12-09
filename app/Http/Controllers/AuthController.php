<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        $token = Auth::login($user);

        return response(['user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!$token = Auth::attempt($credentials)) {
            return response(['message' => 'Invalid credentials'], 401);
        }
        $user = Auth::user();
        $user->api_token = Str::random(60);
        $user->save();

        return response(['token' => $user->api_token]);

    }
}

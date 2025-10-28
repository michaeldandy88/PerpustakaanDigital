<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
        {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Email atau password salah'
                ], 401);
            }

            $user = User::where('email', $request->email)->with('role')->first();

            $token = $user->createToken('app_token')->plainTextToken;

            return response()->json([
                'id'    => $user->id,
                'name'  => $user->name,
                'role'  => $user->role->name,
                'token' => $token,
            ]);
        }
}

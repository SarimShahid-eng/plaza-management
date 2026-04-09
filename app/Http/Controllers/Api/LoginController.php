<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid login details',
            ], 401);
        }

        $user = Auth::user();
        if ($user->role !== 'member' || $user->role !== 'chairman') {
            return response()->json([
                'message' => 'You are not eligible to login ',
            ], 422);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'bearer_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'role'=>$user->role
            ],
            'plaza' => $user->plaza->name,
            'unit_number' => $user->unit->unit_number ?? null,
            'Themecolor' => $user->plazaSetting->color,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
    }
}

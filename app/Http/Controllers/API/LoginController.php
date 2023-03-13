<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('authToken');
            $accessToken = $token->plainTextToken;
            // $refreshToken = $token->refresh_token;

            return response()->json([
                'access_token' => $accessToken,
                // 'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in' => config('sanctum.expiration') * 60000, // token expiry time in seconds
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}

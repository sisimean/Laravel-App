<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register new user
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:logins,email',
            'password' => 'required|min:6',
            'name' => 'nullable|string|max:255',
            'profile' => 'nullable|string|max:255', // ✅ added for optional profile
        ]);

        $user = Login::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'profile' => $request->profile, // ✅ added
        ]);

        return response()->json(['message' => 'User registered', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Login::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'profile' => $user->profile, // ✅ Ensure Flutter gets profile URL
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    /**
     * Register a new customer user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        $token = $user->createToken('customer-token')->plainTextToken;

        return response()->json([
            'message' => 'Customer registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login customer user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->isCustomer()) {
            throw ValidationException::withMessages([
                'email' => ['You are not authorized to access this area.'],
            ]);
        }

        $token = $user->createToken('customer-token')->plainTextToken;

        return response()->json([
            'message' => 'Customer logged in successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout customer user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Customer logged out successfully',
        ]);
    }

    /**
     * Get authenticated customer user
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}

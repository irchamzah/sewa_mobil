<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'driverLicense' => 'required|string|max:50',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'driver_license' => $request->driverLicense,
        ]);

        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek kredensial pengguna
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Jika kredensial valid, dapatkan pengguna
            $user = Auth::user();

            // Generate token JWT atau gunakan Laravel Passport/Token jika ada
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            // Respons dengan token
            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }

        // Jika kredensial tidak valid
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
}

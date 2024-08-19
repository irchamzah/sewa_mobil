<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'driver_license' => 'required|string|unique:users,driver_license,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update($request->only(['name', 'email', 'address', 'phone', 'driver_license']));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        return response()->json(['message' => 'Profile updated successfully']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\User;

class AuthController extends Controller
{
    // Handle user login
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $validated['username'])->first();

        if(!$user || !Auth::Attempt($validated)) {
             
            if(!$user || !Hash::check($validated['password'], $user->password)){
                throw ValidationException::withMessages([
                    'username' => ['The provided credentials are incorrect.'],
                ]);
            }
        }

        $token = $user->createToken('pharmacovigilance-token')->plainTextToken;

        return response()->json([
            'user' => [
                'id'=> $user->id, 
                'username'=> $user->username, 
                'email'=> $user->email, 
            ], 
            'token' => $token
        ]);
    }

    // Handle user logout
    public function logout(Request $request){
        $request->user()->curentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

}

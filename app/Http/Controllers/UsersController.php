<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|string",
            "token" => "nullable|string"
        ]);

        // 🔐 Auth via token
        if ($request->filled('token')) {
            $user = User::where("remember_token", $request->token)->first();

            if ($user) {
                $token = Str::random(60) . ',' . $user->id;

                $user->update([
                    'remember_token' => $token
                ]);

                return response()->json([
                    'status' => 'success',
                    'token' => $token,
                    'user' => $user
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Token invalide'
            ], 401);
        }

        // 🔐 Auth via email/password
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = Str::random(60) . ',' . $user->id;

            $user->update([
                'remember_token' => $token
            ]);

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $user
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email ou mot de passe incorrect'
        ], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->prenom . ' ' . $request->nom,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'typeCompte' => 'client',
            'adresse' => 'karpala',
            'longitude' => 12.234,
            'latitude' => -1.2345,
            'autre' => 'autre',
            'numero' => '09876543',
            'profile' => '34567qwedc.png',
        ]);

        $token = Str::random(60) . ',' . $user->id;

        $user->update([
            'remember_token' => $token
        ]);

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => $user,
        ], 201);
    }
}

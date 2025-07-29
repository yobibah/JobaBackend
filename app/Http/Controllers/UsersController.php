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

        
        if ($request->filled('token')) {
            $user = User::where("remember_token", $request->token)
            ->where("status",'=',0)
            ->first();
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
            
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'typeCompte' => 'client',
            'adresse' => 'karpala',
            'longitude' => 12.234,
            'latitude' => -1.2345,
            'numero' => '09876543',
            'status'=>1 
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

    public function update(Request $request)
{
    $request->validate([
        'token' => 'required',
        'nom' => 'required',
        'prenom' => 'required',
        'profession' => 'required',
        'descriptions' => 'required',
    ]);

    $user = User::where('remember_token', $request->token)->first();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Utilisateur non trouvé'
        ], 404);
    }

    $user->update([
        'prenom' => $request->prenom,
        'nom' => $request->nom,
        'profession' => $request->profession,
        'descriptions' => $request->descriptions,
    ]);

 
    $newToken = Str::random(60) . ',' . $user->id;
    $user->remember_token = $newToken;
    $user->save();

    return response()->json([
        'status' => 'success',
        'token' => $newToken,
        'user' => $user
    ], 200);
}

public function prestataire() {
    
    $users = User::where('typeCompte', 'prestataire')->get();

    return response()->json([
        'message' => 'success',
        'users' => $users
    ], 200);
}

}

<?php

namespace App\Http\Controllers;

use App\Events\RatingEvent;
use App\Mail\InscriptionMail;
use App\Models\Prestataire;
use App\Models\Rating;
use App\Models\User;
use App\Notifications\InscriptionsNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
                ->where("status", '=', 0)
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
            'name' => $request->nom,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => hash::make($request->password),
            'typeCompte' => 'client',
            'adresse' => 'karpala',
            'longitude' => 12.234,
            'latitude' => -1.2345,
            'numero' => '09876543',
            'status' => 1
        ]);
        // $user->notify(new InscriptionsNotifications());
            // Mail::to($user->email)->send(new InscriptionMail($user));
            Mail::to($user->email)->send(new InscriptionMail($user));

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

    public function prestataire()
    {

        $users = User::where('typeCompte', 'prestataire')
            ->get();

        return response()->json([
            'message' => 'success',
            'users' => $users
        ], 200);
    }

    public function detail(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $user = User::find($request->id);
        if (!$user) {
            return response()->json([
                'message' => "une erreur est survenue lors du chargement..."
            ], 404);

        }
        return response()->json([
            'user' => $user,

        ], 200);
    }

public function nextSignup(Request $request)
    {
        // Validation
        $request->validate([
            'token' => 'required|string',
            'adresse' => 'nullable|string',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
            'profile' => 'nullable|file|image',
            'type' => 'nullable|string',
            'metier' => 'nullable|string',
            'numero' => 'required|string',
            'autre' => 'nullable|string',
        ]);

        // Récupérer l'utilisateur via le token
        $user = User::where('remember_token', $request->token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ], 404);
        }

        // Vérifier si le prestataire existe déjà
        if ($request->type === 'prestataire' && Prestataire::where('email', $user->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cet email est déjà utilisé'
            ], 409);
        }

        // Gérer l'image de profil
        $pathname = null;
        if ($request->hasFile('profile')) {
            $path = $request->file('profile')->store('profiles', 'public');
            $pathname = basename($path);
        }

        // Créer le prestataire si nécessaire
        if ($request->type === 'prestataire') {
            Prestataire::create([
                'email' => $user->email,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'profession' => $request->autre ?? $request->metier,
                'adresse' => $request->adresse,
                'numero' => $request->numero,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'token' => $request->token,
                'boosted' => false,
                'whatsapp' => $request->numero,
                'profile' => $pathname,
            ]);
        }

        // Mettre à jour l'utilisateur
        $user->adresse = $request->adresse;
        $user->longitude = $request->longitude;
        $user->latitude = $request->latitude;
        $user->typeCompte = $request->type;
        $user->numero = $request->numero ?? '';
        $user->profile = $pathname ?? $user->profile;
        $user->descriptions = $user->descriptions ?? '';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil modifié avec succès',
            'token' => $user->remember_token
        ], 200);
    }

public function rating(Request $request)
{
    $validator = Validator::make($request->all(), [
        'prestatire_id' => 'required|integer',
        'users_id' => 'required',
        'note' => 'required|integer|min:1|max:5',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()->first()], 500);
    }

    $user = User::where('remember_token', $request->users_id)->first();
    if (!$user) {
        return response()->json(['message' => 'utilisateur non trouvé'], 404);
    }

    $existing = Rating::where('prestataires_id', $request->prestatire_id)
                      ->where('users_id', $user->id)
                      ->first();

    if ($existing) {
        return response()->json(['message' => 'Vous avez déjà noté ce prestataire.'], 500);
    }

    $rating = Rating::create([
        'prestataires_id' => $request->prestatire_id,
        'users_id' => $user->id,
        'notes' => $request->note,
    ]);

    if ($rating) {
        // broadcast
        //vider le cache 
        Cache::forget('notifications');
         Cache::forget('notifications');
          Cache::forget('notifications'+$request->prestatire_id);
        event(new RatingEvent(rating: $rating));


        

        return response()->json(['message' => 'Vous avez noté avec succès !'], 200);
    }

    return response()->json(['message' => 'Une erreur est survenue'], 500);
}
}

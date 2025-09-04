<?php

namespace App\Http\Controllers;

use App\Events\RatingEvent;
use App\Mail\InscriptionMail;
use App\Models\Prestataire;
use App\Models\Rating;
use App\Models\User;
use App\Notifications\InscriptionsNotifications;
use App\Notifications\PrestataireRatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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



public function Prestalogin(Request $request)
{
    $request->validate([
        "email" => "required|email",
        "password" => "required|string",
    ]);

    // Vérifier l'utilisateur par email
    $user = Prestataire::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email ou mot de passe incorrect'
        ], 401);
    }

    // Supprimer les anciens tokens si nécessaire
    $user->tokens()->delete();

    // Créer un nouveau token sanctum
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'token' => $token,
        'user' => $user
    ], 200);
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
            'typeCompte' => '',
            'adresse' => '',
            'longitude' => '',
            'latitude' => '',
            'numero' => '',
            'status' => ''
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
        $validator = Validator::make($request->all(),[
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

        if($validator->fails()){
            Log::info($validator->errors()->all());
        }
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
        $pathname = 'lolo';
        if ($request->hasFile('profile')) {
            $path = $request->file('profile')->store('profiles', 'public');
            $pathname = basename($path);
        }


        $typecomp='prestataire';
        // Créer le prestataire si nécessaire
        if ($request->type === 'prestataire') {
           $prestataire= Prestataire::create([
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
                'typeCompte'=>$typecomp,
                'password'=>$user->password
            ]);

            // $user->delete();
        }

        // Mettre à jour l'utilisateur
    $user->update([
        'adresse' => $request->adresse,
        'longitude' => $request->longitude,
        'latitude' => $request->latitude,
        'typeCompte' => $request->type,
        'numero' => $request->numero ?? '',
        'profile' => $request->hasFile('profile') ? $pathname : $user->profile,
        'descriptions' => $user->descriptions ?? '',
    ]);

    // $prestataire->update([
    //     'profile'=>$user->profile
    // ]);

    $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profil modifié avec succès',
            'token' => $user->remember_token,
            'user'=>$prestataire
        ], 200);
    }

public function rating(Request $request)
{
    $validator = Validator::make($request->all(), [
        'prestatire_id' => 'required|integer',
        'users_id' => 'required',
        'note' => 'required',
    ]);

    if ($validator->fails()) {
        Log::info($validator->errors()->first());
        return response()->json(['message' => $validator->errors()->first()], 500);

    }

    $user = User::where('remember_token', $request->users_id)->first();
     $prestataire = Prestataire::find( $request->prestatire_id);
    if (!$user || !$prestataire) {
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
        // //vider le cache 
        // Cache::forget('notifications');
        //  Cache::forget('notifications');
        //   Cache::forget('notifications'.$request->prestatire_id);
        $prestataire->notify(new PrestataireRatedNotification($rating));
         event(new RatingEvent($rating));



        

        return response()->json(['message' => 'Vous avez noté avec succès !'], 200);
    }

    return response()->json(['message' => 'Une erreur est survenue'], 500);
}

// verifier la veracite de l'utilisateur 

public function verifierClient(Request $request){
    $validator = Validator::make($request->all(),[
      'numero'=>'required',
      'id'=>'required',
      'token'=>'required'
    ]);

    if($validator->fails()){
        Log::info('validation des champs : '.$validator->errors()->all());
        return response()->json([
             'valid' => false,
            'message'=>'une erreur est survenue.'

        ],400);
    }
       $prestataire = Prestataire::where('token',$request->token)->first();
        if(!$prestataire){
            return response()->json([
                 'valid' => false,
                'message'=> 'token invalide'
            ],404);
        }

        $user=User::where('id',$request->id)->where('numero',$request->numero)->first();
            if(!$user){
            return response()->json([
                'valid' => false,
                'message'=> 'utilsateur non trouve(e)'
            ],404);
        }

      return response()->json([
            'valid' => true,
            'client' => [
                'id' => $user->id,
                'email' => $user->email
            ]
        ]);
    

}

}

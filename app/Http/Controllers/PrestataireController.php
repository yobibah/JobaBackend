<?php

namespace App\Http\Controllers;

use App\Models\horaires;
use App\Models\Rating;
use App\Models\User;
use App\Models\Exploits;
use Exception;
use GuzzleHttp\Psr7\Query;
use App\Models\Prestataire;
use   \Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notification;
use Illuminate\Session\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PrestataireController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
            'token' => 'nullable|string'
        ]);
        //connexion avec token

        if ($request->filled('token')) {
            $user = Prestataire::where('token', $request->token)->first();
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
                'message' => 'token invalide'
            ], 401);


        }


        // connexion avec donners saisi 
        $prestataire = Prestataire::where("email", $request->email)->first();
        if ($prestataire && Hash::make($prestataire->password, $request->password)) {
            $token = Str::random(60) . ',' . $prestataire->id;
            $prestataire->update([
                'token' => $token
            ]);
            return response()->json([
                'message' => 'connexion reussie',
                'user' => $prestataire

            ], 200);

        }
        return response()->json([
            'status' => 'error',
            'message' => 'donnee invalide veuillez renseigner les bonnes donnees'
        ], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'prenom',
            'nom' => 'required',
            'email' => 'required',
            'password' => 'required',
            'profession' => 'required',
            'adresse' => 'required',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'numero' => 'required',
        ]);
        $user = Prestataire::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'message' => 'cet email est deja affilie a un compte'
            ], 401);
        } else {
            $token = Str::random(60) . ',' . $user->id;
            Prestataire::created([
                'email' => $request->email,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'profession' => $request->profession,
                'adresse' => $request->adresse,
                'numero' => $request->numero,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'token' => $token,
                'boosted' => false,
            ]);
            Cache::forget('users');
            return response()->json([
                'message' => 'inscriptions reussi..'
            ], 200);
        }
    }

    public function AddProfile(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'profile' => 'nullable|nimes:png,jpg,svg,jpeg',
        ]);
        $user = Prestataire::where('token', $request->token)->first();
        if ($user) {
            $path = $request->file('profile')->Store('profiles', 'public');
            $name = basename($path);
            Prestataire::update([
                'profile' => $name
            ]);

            return response()->json([
                'message' => 'profile mise a jour avec succes'
            ], 200);
        }


    }

    public function update(Request $request)
    {
        $request->validate([
            'token',
            'descriptions',
            'profile',
            'whatsapp',
            'profession',
            'nom',
            'prenom'
        ]);
        $user = Prestataire::where('token', $request->token)->first();
        if ($user) {
            if ($request->hasFile('profile')) {
                $path = $request->file('profile')->Store('profiles', 'public');
                $name = basename($path);
            }
            $user->update([
                'profile' => $name,
                'descriptions' => $request->descriptions,
                'whatsapp' => $request->whatsapp,
                'profession' => $request->profession,
                'nom' => $request->nom,
                'prenom' => $request->prenom,

            ]);
            return response()->json([
                'message' => 'votre profile a ete modifier avec succes'
            ], 200);

        }
        return response()->json([
            'message' => 'une erreur est survenue de lors de la misea jour'
        ], 200);

    }
    public function prestataire(Request $request)
    {
        // Facultatif : validation du token
        // $request->validate([
        //     'token' => 'required'
        // ]);
        $validator = Validator::make($request->all(),[
            'token' => 'required'
        ]);

        if($validator->fails()){
            Log::info('putin de token '.$validator->errors()->all());
               return response()->json([
                'status' => 'error',
                'message' => "Aucun prestataire disponible pour l'instant."
            ],400);
        }
        $token = $request->token;
        // Récupération de tous les prestataires dans un ordre aléatoire
      $users = 
     Prestataire::where('token', '!=', $token)->get();

  Log::info($users);

           
        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => "Aucun prestataire disponible pour l'instant."
            ],404);
        }

        // Ajout du rating pour chaque prestataire
        $usersWithRatings = $users->map(function ($user) {

            $ratingCount = Cache::remember('ratingCount_' . $user->id, 10, function () use ($user) {
                return rating::where('prestataires_id', $user->id)->count();
            });


            $ratingSum = Cache::remember('ratingSum_' . $user->id, 10, function () use ($user) {
                return rating::where('prestataires_id', $user->id)->sum('notes');
            });

            $user->rating = $ratingCount > 0 ? $ratingSum / $ratingCount : null;
            return $user;
        });

        return response()->json([
            'message' => 'success',
            'users' => $usersWithRatings,
        ], 200);
    }

    // public function prestataire() {

    //     $users = User::where('typeCompte', 'prestataire')
//     ->get();

    //     return response()->json([
//         'message' => 'success',
//         'users' => $users
//     ], 200);
// }



    public function StoreExploit(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'token' => 'required',
            'user_id' => 'required',
            'caption' => 'required|string',
            'image'=>'required'
        ]);

        if($validator->fails()){
            Log::info($validator->errors()->all());
            return response()->json([
                'message'=>'veuillez remplir correctement les champs'
            ],402);
        }

        $prestataire = Prestataire::where('token', $request->token)->first();
        if ($prestataire) {
            if (filled('image')) {
                $path = $request->file('image')->store('exploits', 'public');
                $image = basename($path);
            }



            
          $exploits=  Exploits::create([
                'users_id' => $request->user_id,
                'caption' => $request->caption,
                'image' => $image,
                'video' =>  'null',
                'prestataire_id' => $prestataire->id,
            ]);
            if(!$exploits){
            Log::error( $exploits);
            return response()->json([
                'message' => 'une erreur est survenue',
            ],500);
            }

            return response()->json([
                'message' => 'exploits creer avec succes',
            ],201);

        }
       

    }

    public function DeleteExploit(Request $request)
    {
        $request->validate([
            "token" => "required",
            "id_exploit" => "required"
        ]);
        $validator = Validator::make($request->all(),[
          
             "token" => "required",
            "id_exploit" => "required"
        ]);
        if($validator->fails()){
            Log::info($validator->errors()->all());
            return response()->json([
                  'message'=>'token ou exploits invalide',
            ]);
        }
        $prestataire = Prestataire::where("token", $request->token)->first();
        if ($prestataire) {
            $exploit = Exploits::where("id", $request->id_exploit)
                ->where("prestataire_id", $prestataire->id)
                ->first();
            if ($exploit) {
                  if ($exploit->image && Storage::disk('public')->exists($exploit->image)) {
                    Storage::disk('public')->delete($exploit->image);
                 }
                $exploit->delete();
                return response()->json([
                    "message" => "exploit supprimer avec succes ..."
                ], 200);
            }
        }
        Log::error($prestataire);
        return response()->json([
            "message" => "une erreur est survenue lors de la supression..."
        ], 400);

    }

    public function updateExploit(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'users_id' => 'required',
            'caption' => 'required|alpha',
            'image' => 'required',
            'video',
            'id_exploits' => 'required',
        ]);
        $prestataire = Prestataire::where('token', $request->token)->first();
        if ($prestataire) {
            $exploit = Exploits::where('id', $request->id_exploit)
                ->where('prestataire_id', $prestataire->id)
                ->first();
            if ($exploit) {
                $exploit->update([
                    'caption' => $request->caption,
                    'image' => $request->image,
                    'video' => $request->video || null,
                ]);

                return response()->json([
                    'message' => 'exploit mise a jour avec succes..'
                ]);

            }
        }
        return response()->json([
            'message' => 'une erreur est survenue lors de la modifications..'
        ], 400);
    }



    public function MesExploits(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $prestataire = Prestataire::where('token', $request->token)->first();
        if ($prestataire) {
            $exploit = Exploits:: with(['users'=>function($q){
                $q->select('users.id','users.nom','users.prenom','users.adresse');
            }])->where('prestataire_id', $prestataire->id)->get();
            if ($exploit->isNotEmpty()) {
                return response()->json([
                    'exploits' => $exploit
                ], 200);
            } else {
                return response()->json([
                    'message' => "vous n'avez pas encore d'exploits"
                ], 404);
            }

        } else {
            return response()->json([
                "message" => "token invalide veuillez vous reconnecter"
            ], 404);
        }

    }
    public function ExploitPrestataire(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);
        $exploits = Exploits::where('prestataires_id', $request->id)->get();
        if ($exploits->count() > 0) {
            return response()->json([
                'exploits' => $exploits
            ], 200);
        }
    }

    public function MyRating(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $user = Prestataire::where('token', $request->token)->first();
        if ($user) {

            $exploits = Exploits::where('prestataires_id', $user->id);
            $count = $exploits->count();
            $somme = $exploits->sum('note');
            if ($count == 0) {
                return response()->json([
                    'message' => "vous n'etes pas encore note"
                ], 200);
            }
            $rating = $somme / $count;
            return response()->json([
                'rating' => $rating,
                'nombre' => $count
            ], 200);

        } else {
            return response()->json([
                'message' => 'token invalide'
            ], 404);
        }

    }

    public function MesHoraires(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'token' => 'required',
        ]);

           if($validator->fails()){
            Log::info($validator->errors()->all());
            return response()->json([
                'message'=>'erreur de token'
            ],402);
        }
        $user = Prestataire::where('token', $request->token)->first();
        if ($user) {
            $horaires = horaires::where('prestataire_id', $user->id);
            if ($horaires->count() > 0) {
                return response()->json([
                    'horaires' => $horaires
                ], 200);

            } else {
                return response()->json([
                    'message' => "vous n'avez pas definis vos horaires"
                ], 404);
            }

        } else {
            response()->json([
                'message' => 'token invalide'
            ]);
        }
    }


    // recuperer sous formes de tableau et les dispatch pour en faire des donnes differentes
    public function AddHoraires(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'tab' => 'required'
        ]);
        $user = Prestataire::where('token', $request->token)->first();
        if ($user) {
            foreach ($request->tab as $value) {
                horaires::created([
                    'prestataires_id' => $user->id,
                    'heure_ouv' => $value['heure'],
                    'jour_ouv' => $value['jour'],

                ]);


            }
            return response()->json([
                'message' => 'horaire mise a jour..',
            ]);
        } else {
            return response()->json([
                'message' => 'token invalide'
            ], 404);
        }

    }

    public function detail(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $prestataire = Prestataire::find($request->id);

        if (!$prestataire) {
            return response()->json([
                'message' => "une erreur est survenue lors du chargement..."
            ], 404);
        }

        $ratingCount = Rating::where('prestataires_id', $request->id)->count();
        $RatingSum = rating::where('prestataires_id', $request->id)->sum('notes');
        $rating = $ratingCount > 0 ? $RatingSum / $ratingCount : null;

        $exploits = Exploits::where('prestataire_id', $request->id)->get();
        $horaires = horaires::where('id_prestataires', $request->id)->get();

        return response()->json([
            'user' => $prestataire,
            'ratings' => $rating,
            'nombre_notes' => $ratingCount,
            'exploits' => $exploits,
            'horaires' => $horaires,
        ], 200);
    }

    public function prestataireVedette()
    {
        $prestataires = Cache::remember('prestataire_vedette', 600, function () {
            return Prestataire::select('nom', 'prenom', 'profile', 'id', 'profession', 'adresse', 'numero', 'rating','typeCompte')->where('boosted', 1)
                ->get()
                ->map(function ($item) {
                    $ratingCount = Rating::where('prestataires_id', $item->id)->count();
                    $ratingSum = Rating::where('prestataires_id', $item->id)->sum('notes');
                    $rating = $ratingCount > 0 ? round($ratingSum / $ratingCount, 2) : null;


                    $item->rating = $rating;

                    return $item;
                })
                ->filter(function ($item) {
                    return $item->rating !== null && $item->rating >= 4;
                })
                ->values();
        });

        return response()->json([
            'users' => $prestataires,
            'from' => Cache::has('prestataire_vedette') ? 'cache' : 'database'
        ]);
    }
    public function notifi(Request $request)
    {
        $request->validate(['token' => 'required']);

        $prestataire = Prestataire::find($request->token);
        if (!$prestataire) {
            return response()->json(['message' => 'Prestataire non trouvé'], 404);
        }

        $unread = DB::table('notifications')
            ->where('notifiable_id', $prestataire->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notif) {
                $data = json_decode($notif->data, true);
                return [
                    'id' => $notif->id,
                    'note' => $data['note'] ?? null,
                    'message' => $data['message'] ?? null,
                    'user' => User::find($data['user_id'] ?? null),
                    'created_at' => $notif->created_at,
                ];
            });

        $read = DB::table('notifications')
            ->where('notifiable_id', $prestataire->id)
            ->whereNotNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notif) {
                $data = json_decode($notif->data, true);
                return [
                    'id' => $notif->id,
                    'note' => $data['note'] ?? null,
                    'message' => $data['message'] ?? null,
                    'user' => User::find($data['user_id'] ?? null),
                    'created_at' => $notif->created_at,
                ];
            });

        return response()->json([
            'notifications' => $unread,
            'lus' => $read
        ]);
    }

    public function readNotif(Request $request)
    {
        $request->validate(['token' => 'required']);

        $prestataire = Prestataire::find($request->token);
        if (!$prestataire) {
            return response()->json(['message' => 'Prestataire non trouvé'], 404);
        }

        DB::table('notifications')
            ->where('notifiable_id', $prestataire->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        Cache::forget('notifications' . $prestataire->id);

        return response()->json(['message' => 'Toutes les notifications ont été lues']);
    }


    public function NotifNonLu(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()->all()
            ], 402);
        }
        $prestataire = Prestataire::find($request->token);
        if (!$prestataire) {
            return response()->json([
                'message' => 'prestataire non trouvé'
            ]);
        }
        // $cachekey = 'notifications' . $prestataire->id;

        $notifs = 
             DB::table('notifications')
                ->where('notifiable_id', $prestataire->id)
                ->whereNull('read_at')
                ->count();
       
        Log::info(Cache::get('notifications16') . 'cacher');
        Log::info($notifs . 'notifs');

        return response()->json([
            'notifications' => $notifs
        ]);

    }


    public function PersonneQuiMaNoter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tokens' => 'required',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'une erreur est survenue.'

            ], 422);
        }

        try {
            $prestataire = Prestataire::where('token', $request->tokens)->first();

        
            if (!$prestataire) {
                return response()->json([
                    'message' => 'prestataire non trouver :).'
                ], 404);

            }

            $user_id=$request->user_id;

         $user =  DB::table('notifications')
            ->where('notifiable_id', $prestataire->id)
            ->get()
            ->first(function ($notif) use ($user_id) {
                $data = json_decode($notif->data, true);
                return isset($data['user_id']) && $data['user_id'] == $user_id;
            });


        // retourner les details de l'utilsateur

        $utilis = User::find($user_id);
    
        
        if(!$user){
            return response()->json([
                'message'=>"cet utilisateur n'a pas note le prestataire"
            ],404);
        }
        if($utilis){
            $utilis->email = Str::mask($utilis->email,"*",2,5);
        }

        return response()->json([
            'users'=>$utilis
        ],200);

        }

        catch(Exception $e){
            Log::info('personne qui ma noter son catch'.$e->getMessage());
        }
        
    }


public function like($q)
{
    try {
        $prestataires = Cache::remember('prestataire_',30,function () use($q){
              return  Prestataire::where('prestataire', 'like', "%{$q}%")->get();
        });
      

        return response()->json([
            'status' => true,
            'data' => $prestataires
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Erreur : ' . $e->getMessage()
        ], 500);
    }
}


}
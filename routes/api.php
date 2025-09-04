<?php

use App\Http\Controllers\LikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PrestataireController;

Route::post('/registerNex',[UsersController::class,'nextSignup']);
Route::post('/login',[UsersController::class,'Login']);
Route::post('/prestataireLogin',[UsersController::class,'prestaLogin']);
Route::post('/register',[UsersController::class,'register']);
Route::put('/update',[UsersController::class,'update']);

Route::post('/noting',[UsersController::class,'rating']);
Route::post('/verifier-client',[UsersController::class,'verifierClient']);

Route::post('/prestataire',[PrestataireController::class,'prestataire']);
Route::post('/detail',[PrestataireController::class,'detail']);
Route::get('/prestataire-vedette',[PrestataireController::class,'prestataireVedette']);

Route::post('/mes-exploits',[PrestataireController::class,'MesExploits']);
Route::post('/Exploits',[PrestataireController::class,'StoreExploit']);
route::post('/delete-exploits',[PrestataireController::class,'DeleteExploit']);
//prestataire
Route::post('loginp',[PrestataireController::class,'login']);


// Route pour récupérer les notifications non lues
Route::post('/notifi', [PrestataireController::class, 'notifi']);

// Route pour marquer toutes les notifications comme lues
Route::post('/readNotif', [PrestataireController::class, 'readNotif']);
Route::post('/notifinotread', [PrestataireController::class, 'NotifNonLu']);
Route::post('/personne', [PrestataireController::class, 'PersonneQuiMaNoter']);

Broadcast::routes(['middleware' => ['auth:api']]);
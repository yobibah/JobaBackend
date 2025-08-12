<?php

use App\Http\Controllers\LikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PrestataireController;

Route::post('/registerNex',[UsersController::class,'nextSignup']);
Route::post('/login',[UsersController::class,'Login']);
Route::post('/register',[UsersController::class,'register']);
Route::put('/update',[UsersController::class,'update']);

Route::post('/noting',[UsersController::class,'rating']);

Route::post('/prestataire',[PrestataireController::class,'prestataire']);
Route::post('/detail',[PrestataireController::class,'detail']);

//prestataire
Route::post('loginp',[PrestataireController::class,'login']);

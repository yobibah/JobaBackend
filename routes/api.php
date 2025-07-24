<?php

use App\Http\Controllers\LikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;



Route::post('/login',[UsersController::class,'Login']);
Route::post('/register',[UsersController::class,'register']);
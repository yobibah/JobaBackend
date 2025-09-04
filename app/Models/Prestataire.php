<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class Prestataire extends Model
{
    use Notifiable,HasApiTokens;
    //
        protected $fillable = [
        'prenom',
        'nom',
        'email',
        'password',
        'profession',
        'adresse',
        'longitude',
        'latitude',
        'numero',
        'token',
        'descriptions',
        'profile',
        'status',
        'boosted',
        'whatsapp',
        'typeCompte'
    ];

    // protected $hidden = ['profile'];
    // protected $appends = ['profile_url'];

    // public function getProfileUrlAttribute()
    // {
    //     return Storage::url($this->profile);
    // }
}
         
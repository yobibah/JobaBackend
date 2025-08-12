<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Prestataire extends Model
{
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
        'whatsapp'
    ];

    protected $hidden = ['profile'];
    protected $appends = ['profile'];

    public function getImageUrlAttribute()
    {
        return Storage::url($this->profile);
    }
}
         
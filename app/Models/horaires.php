<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class horaires extends Model
{
    protected $fillable = [
        'heure_ouv',
        'jour_ouv',
    ];
}

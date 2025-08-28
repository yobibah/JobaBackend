<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    // Table associée
    protected $table = 'ratings'; // ou 'rating' si tu utilises ce nom

    // Colonnes assignables en masse
    protected $fillable = [
        'prestataires_id',
        'users_id',
        'notes',
    ];

    // Relations

    /**
     * L'utilisateur qui a donné la note
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Le prestataire qui reçoit la note
     */
    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class, 'prestataires_id');
    }
}

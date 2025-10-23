<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasUuid;

class Client extends Model
{
    use HasFactory, HasUuid;
    // Nom de la table
    protected $table = 'clients';

   protected $fillable = ['user_id', 'telephone', 'points_fidelite'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comptes()
{
    return $this->hasMany(Compte::class, 'client_id');
}

public function scopeTelephone($query, $numero)
{
    return $query->where('telephone', $numero);
}



    // (Optionnel) pour transformer automatiquement certains champs
    protected $casts = [
        'date_naissance' => 'date',
    ];

    // (Optionnel) Attribut personnalisé (exemple)
    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        return $this->user ? "{$this->user->prenom} {$this->user->nom}" : 'Inconnu(e)';
    }
}

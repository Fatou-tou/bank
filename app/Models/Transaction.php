<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Transaction extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['compte_id', 'type', 'montant'];

    public function compte()
    {
        return $this->belongsTo(Compte::class, 'compte_id');
    }
}

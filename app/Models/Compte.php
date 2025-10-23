<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Traits\HasUuid;
use App\Traits\HasAdvancedQueryScopes;

/**
 * @OA\Schema(
 *     schema="Compte",
 *     title="Compte",
 *     description="Modèle représentant un compte bancaire",
 *     @OA\Property(property="id", type="string", description="UUID du compte"),
 *     @OA\Property(property="numeroCompte", type="string", description="Numéro du compte"),
 *     @OA\Property(property="titulaire", type="string", description="Nom complet du titulaire"),
 *     @OA\Property(property="type", type="string", enum={"epargne", "cheque"}, description="Type de compte"),
 *     @OA\Property(property="solde", type="number", format="float", description="Solde du compte"),
 *     @OA\Property(property="devise", type="string", description="Devise du compte"),
 *     @OA\Property(property="dateCreation", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "bloque"}, description="Statut du compte"),
 *     @OA\Property(property="motifBlocage", type="string", description="Motif de blocage si applicable"),
 *     @OA\Property(property="metadata", type="object",
 *         @OA\Property(property="derniereModification", type="string", format="date-time")
 *     )
 * )
 */
class Compte extends Model
{
    use HasFactory, HasUuid, HasAdvancedQueryScopes;

    protected $fillable = ['client_id', 'numero_compte', 'type', 'devise', 'motifBlocage'];

    protected static function booted()
    {
        static::addGlobalScope('nonSupprime', function (Builder $builder) {
            $builder->where('supprime', false);

        });
    }

    /**
     * Colonnes autorisées pour la recherche
     */
    protected function getSearchableColumns(): array
    {
        return ['numero_compte'];
    }

    /**
     * Colonnes autorisées pour le tri
     */
    protected function getSortableColumns(): array
    {
        return ['created_at', 'solde', 'client.full_name'];
    }

    /**
     * Colonnes autorisées pour le filtrage
     */
    protected function getFilterableColumns(): array
    {
        return ['type', 'statut', 'devise'];
    }

    public function scopeNumero($query, $numero)
    {
        return $query->where('numero_compte', $numero);
    }

    public function scopeClient($query, $telephone)
    {
        return $query->whereHas('client', function ($q) use ($telephone) {
            $q->where('telephone', $telephone);
        });
    }

    /**
     * Scope personnalisé pour le filtrage par type avec validation
     */
    public function scopeFiltrerParType($query, $type)
    {
        if ($type && in_array($type, ['epargne', 'cheque'])) {
            return $query->where('type', $type);
        }
        return $query;
    }

    /**
     * Scope personnalisé pour le filtrage par statut avec mapping
     */
    public function scopeFiltrerParStatut($query, $statut)
    {
        if ($statut && in_array($statut, ['actif', 'bloque', 'ferme'])) {
            $statutMapping = [
                'actif' => 'actif',
                'bloque' => 'bloqué',
                'ferme' => 'fermé'
            ];
            return $query->where('statut', $statutMapping[$statut]);
        }
        return $query;
    }

    /**
     * Scope personnalisé pour le tri avec logique complexe
     */
    public function scopeTrier($query, $sortBy = 'dateCreation', $order = 'asc')
    {
        $allowedSorts = ['dateCreation', 'solde', 'titulaire'];
        if (!in_array($sortBy, $allowedSorts)) {
            return $query;
        }

        $sortMapping = [
            'dateCreation' => 'created_at',
            'solde' => 'calculated_solde',
            'titulaire' => 'clients.full_name'
        ];

        if ($sortBy === 'titulaire') {
            return $query->join('clients', 'comptes.client_id', '=', 'clients.id')
                         ->orderBy('clients.full_name', $order);
        } elseif ($sortBy === 'solde') {
            return $query->leftJoin('transactions', 'comptes.id', '=', 'transactions.compte_id')
                         ->selectRaw('comptes.*, COALESCE(SUM(CASE WHEN transactions.type = "depot" THEN transactions.montant ELSE 0 END) - SUM(CASE WHEN transactions.type = "retrait" THEN transactions.montant ELSE 0 END), 0) as calculated_solde')
                         ->groupBy('comptes.id')
                         ->orderBy('calculated_solde', $order);
        } else {
            return $query->orderBy($sortMapping[$sortBy], $order);
        }
    }

    public function setNumeroCompteAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['numero_compte'] = 'CPT-' . strtoupper(Str::random(8));
        } else {
            $this->attributes['numero_compte'] = $value;
        }
    }

    public function client()
{
    return $this->belongsTo(Client::class, 'client_id');
}

public function transactions()
    {
        return $this->hasMany(Transaction::class, 'compte_id');
    }

     // ✅ Calcul automatique du solde
    public function getSoldeAttribute()
    {
        $depot = $this->transactions()->where('type', 'depot')->sum('montant');
        $retrait = $this->transactions()->where('type', 'retrait')->sum('montant');
        return $depot - $retrait;
    }
    protected $appends = ['solde'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s\Z',
        'updated_at' => 'datetime:Y-m-d\TH:i:s\Z',
    ];

    public function toArray()
    {
        $array = parent::toArray();

        return [
            'id' => $this->id,
            'numeroCompte' => $this->numero_compte,
            'titulaire' => $this->client->full_name ?? 'Inconnu(e)',
            'type' => strtolower($this->type),
            'solde' => $this->solde,
            'devise' => $this->devise,
            'dateCreation' => $this->created_at->toISOString(),
            'statut' => $this->statut === 'bloqué' ? 'bloque' : 'actif',
            'motifBlocage' => $this->motifBlocage,
            'metadata' => [
                'derniereModification' => $this->updated_at->toISOString(),
            ],
        ];
    }

}

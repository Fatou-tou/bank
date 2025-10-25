<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    /**
     * Calcule le solde du compte à partir des transactions
     */
    private function calculateBalance(): float
    {
        return $this->transactions()
            ->selectRaw("COALESCE(SUM(CASE 
                WHEN type = 'depot' THEN montant 
                WHEN type = 'retrait' THEN -montant 
                ELSE 0 
            END), 0) as balance")
            ->value('balance');
    }

    public function toArray(Request $request): array
    {
        // Calcul du solde
        // Calcul du solde via les transactions chargées
        $solde = $this->transactions->reduce(function($carry, $t) {
            if ($t->type === 'depot') {
                return $carry + $t->montant;
            } elseif ($t->type === 'retrait') {
                return $carry - $t->montant;
            }
            return $carry;
        }, 0);
        
        return [
            'id' => $this->id,
            'numeroCompte' => $this->numero_compte,
            'titulaire' => $this->client->full_name ?? 'Inconnu(e)',
            'type' => strtolower($this->type),
            'solde' => $solde,
            'devise' => $this->devise ?? 'FCFA',
            'dateCreation' => $this->created_at->toIso8601String(),
            'statut' => $this->statut ?? 'actif',
            'motifBlocage' => $this->motifBlocage,
            'metadata' => [
                'derniereModification' => $this->updated_at->toIso8601String(),
                'version' => 1
            ]
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Compte>
 */
class CompteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'numero_compte' => null, // Le mutator générera automatiquement le numéro
            'type' => $this->faker->randomElement(['Epargne', 'Courant', 'Cheque']),
            'statut' => $this->faker->randomElement(['actif', 'bloqué']),
            'devise' => $this->faker->randomElement(['FCFA']),
            'motifBlocage' => $this->faker->optional(0.3)->sentence(),
            'supprime' => false,
        ];
    }
}

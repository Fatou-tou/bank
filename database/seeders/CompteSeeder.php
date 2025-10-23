<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use App\Models\Compte;
use App\Models\Client;

class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des comptes pour les clients existants
        $clients = Client::all();
        foreach ($clients as $client) {
            Compte::factory()->create([
                'client_id' => $client->id,
            ]);
        }

        // Log pour diagnostic
        Log::info('CompteSeeder: Créé ' . Compte::count() . ' comptes pour ' . $clients->count() . ' clients');
        Log::info('Nombre total de clients: ' . Client::count());
        Log::info('Comptes sans client valide: ' . Compte::whereDoesntHave('client')->count());
    }
}

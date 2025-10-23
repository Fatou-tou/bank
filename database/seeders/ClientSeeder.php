<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On crée 10 clients aléatoires avec la factory
        Client::factory(10)->create();

        // Log pour diagnostic
        Log::info('ClientSeeder: Créé ' . Client::count() . ' clients');
    }
}

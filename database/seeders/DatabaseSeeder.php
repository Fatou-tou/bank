<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // On appelle notre seeder personnalisé
        $this->call([
            ClientSeeder::class,
            CompteSeeder::class,
            AdminSeeder::class,
        ]);
    }
}

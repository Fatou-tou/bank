<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('users', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('prenom');
    $table->string('nom');
    $table->string('email')->unique();
    $table->string('adresse')->nullable();
    $table->date('date_naissance')->nullable();
    $table->enum('genre', ['Homme', 'Femme'])->nullable();
    $table->string('password');
    $table->string('type')->default('client');
    $table->rememberToken();
    $table->timestamps();

    $table->index(['prenom', 'nom']); // pour les recherches sur le nom complet
    $table->index('type');             // pour filtrer par type (client/admin)
    $table->index('created_at');       // pour trier par date d'inscription
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

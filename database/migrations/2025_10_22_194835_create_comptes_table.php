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
        Schema::create('comptes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('client_id'); // clé étrangère UUID
            $table->enum('type', ['Epargne', 'Courant', 'Cheque']);

            $table->string('numero_compte')->unique();
            $table->enum('statut', ['actif', 'bloqué'])->default('actif');
            $table->string('devise')->default('FCFA');
            $table->text('motifBlocage')->nullable();
            // $table->enum('supprime', ['non supprimé', 'supprimé'])->default('non supprimé');
            $table->boolean('supprime')->default(false);
            $table->dateTime('date_deblocage')->nullable();
            $table->timestamps();

            // clé étrangère vers la table clients
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $table->index(['numero_compte', 'created_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};

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
        Schema::create('clients', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
    $table->string('telephone')->unique();
    $table->timestamps();

    $table->index('user_id');          // pour les jointures fréquentes
    $table->index('telephone');        // pour rechercher un client via téléphone
    $table->index('created_at'); 
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

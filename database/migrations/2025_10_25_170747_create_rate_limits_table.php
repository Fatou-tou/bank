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
         Schema::create('rate_limits', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade');
        $table->string('ip', 45)->nullable();
        $table->string('endpoint');
        $table->timestamp('exceeded_at');
        $table->timestamps();

        $table->index(['user_id', 'exceeded_at']);
        $table->index(['ip', 'exceeded_at']);
        $table->index('endpoint');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_limits');
    }
};

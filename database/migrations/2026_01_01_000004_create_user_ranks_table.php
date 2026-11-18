<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_ranks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rank_id')->constrained('ranks')->cascadeOnDelete();
            $table->unsignedBigInteger('achieved_xp')->default(0);
            $table->timestamp('assigned_at');
            $table->timestamps();

            $table->unique(['user_id', 'rank_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ranks');
    }
};

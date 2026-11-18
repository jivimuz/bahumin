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
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->integer('level')->unique();
            $table->string('name');
            $table->string('description');
            $table->integer('min_xp')->default(0);
            $table->string('badge_icon')->nullable();
            $table->string('color')->default('#C2410C');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->unsignedBigInteger('total_xp')->default(0)->after('bio');
            $table->foreignId('rank_id')->nullable()->after('total_xp')->constrained('ranks')->nullOnDelete();
            $table->integer('streak_days')->default(0)->after('rank_id');
            $table->boolean('is_admin')->default(false)->after('streak_days');
        });

        Schema::create('user_xp_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('amount');
            $table->string('reason');
            $table->string('reference_type')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('idempotency_key')->unique();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description');
            $table->string('icon')->default('🏆');
            $table->string('requirement_type'); // e.g. quiz_completed, perfect_score, xp_milestone, jokes_posted
            $table->integer('requirement_value')->default(1);
            $table->integer('xp_bonus')->default(25);
            $table->timestamps();
        });

        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained('achievements')->cascadeOnDelete();
            $table->timestamp('unlocked_at');
            $table->timestamps();

            $table->unique(['user_id', 'achievement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('user_xp_transactions');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rank_id']);
            $table->dropColumn(['username', 'avatar', 'bio', 'total_xp', 'rank_id', 'streak_days', 'is_admin']);
        });

        Schema::dropIfExists('ranks');
    }
};

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
        Schema::create('jokes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('setup');
            $table->text('punchline')->nullable();
            $table->string('status')->default('published');
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });

        Schema::create('joke_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('joke_id')->constrained('jokes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('reaction_type'); // ngakak, garing, bapak, jenius
            $table->timestamps();

            $table->unique(['joke_id', 'user_id']);
        });

        Schema::create('joke_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('joke_id')->constrained('jokes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('comment_text');
            $table->timestamps();
        });

        Schema::create('joke_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('joke_id')->constrained('jokes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['joke_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joke_bookmarks');
        Schema::dropIfExists('joke_comments');
        Schema::dropIfExists('joke_reactions');
        Schema::dropIfExists('jokes');
    }
};

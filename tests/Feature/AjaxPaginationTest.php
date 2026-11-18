<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Joke;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AjaxPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_jokes_are_paginated_and_only_expose_public_user_fields(): void
    {
        $user = $this->makeUser();
        $category = Category::create(['name' => 'Warkop', 'slug' => 'warkop', 'is_active' => true]);
        for ($i = 1; $i <= 10; $i++) {
            Joke::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'setup' => "Pertanyaan jokes nomor {$i}",
                'punchline' => 'Jawaban jokes',
                'status' => 'published',
            ]);
        }

        $this->getJson('/api/jokes')
            ->assertOk()
            ->assertJsonCount(9, 'data')
            ->assertJsonPath('current_page', 1)
            ->assertJsonPath('last_page', 2)
            ->assertJsonMissingPath('data.0.user.email')
            ->assertJsonMissingPath('data.0.user.is_admin');

        $this->getJson('/api/jokes?page=2')->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_admin_lists_return_paginated_json(): void
    {
        $admin = $this->makeUser(true);
        Category::create(['name' => 'Warkop', 'slug' => 'warkop', 'is_active' => true]);

        foreach (['/admin/users', '/admin/jokes', '/admin/categories'] as $path) {
            $this->actingAs($admin)->getJson($path)
                ->assertOk()
                ->assertJsonStructure(['data', 'current_page', 'last_page', 'next_page_url', 'prev_page_url']);
        }
    }

    public function test_leaderboard_and_quiz_history_return_paginated_json(): void
    {
        $user = $this->makeUser();

        $this->getJson('/api/leaderboard')->assertOk()->assertJsonStructure(['data', 'current_page', 'last_page']);
        $this->getJson('/api/leaderboard?tab=weekly')->assertOk()->assertJsonStructure(['data', 'current_page', 'last_page']);
        $this->actingAs($user)->getJson('/history')->assertOk()->assertJsonStructure(['data', 'current_page', 'last_page']);
    }

    private function makeUser(bool $admin = false): User
    {
        static $sequence = 0;
        $sequence++;

        return User::create([
            'name' => 'User '.$sequence,
            'username' => 'user_'.$sequence,
            'email' => 'user_'.$sequence.'@example.test',
            'password' => 'password',
            'is_admin' => $admin,
            'total_xp' => $sequence,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Joke;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_render_successfully(): void
    {
        $joke = Joke::firstOrFail();
        $user = User::whereNotNull('username')->firstOrFail();

        foreach (['/', '/jokes', '/leaderboard', '/login', '/register', "/jokes/{$joke->id}", "/user/{$user->username}"] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_authenticated_menu_pages_render_successfully(): void
    {
        $user = User::where('is_admin', true)->firstOrFail();

        foreach (['/quiz', '/history', '/profile', '/profile/edit', '/jokes-create'] as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
    }
}

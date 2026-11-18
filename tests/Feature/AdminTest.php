<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Joke;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_login_uses_dedicated_portal_and_rejects_regular_user(): void
    {
        $admin = $this->makeUser(true);
        $user = $this->makeUser();

        $this->get('/admin/login')->assertOk()->assertSee('Login admin');

        $this->post('/admin/login', [
            'login' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('login');
        $this->assertGuest();

        $this->post('/admin/login', [
            'login' => $admin->email,
            'password' => 'password',
        ])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_regular_user_cannot_access_admin(): void
    {
        $this->actingAs($this->makeUser())->get('/admin')->assertForbidden();
    }

    public function test_admin_can_open_dashboard(): void
    {
        $this->actingAs($this->makeUser(true))
            ->get('/admin')
            ->assertOk()
            ->assertSee('Konsol admin');
    }

    public function test_admin_can_create_dummy_user_and_joke_visible_in_catalog(): void
    {
        $admin = $this->makeUser(true);
        $category = Category::create([
            'name' => 'Tebakan Warkop',
            'slug' => 'tebakan-warkop',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->post('/admin/dummy', [
            'category_id' => $category->id,
        ])->assertRedirect('/admin/jokes');

        $dummy = User::where('email', 'like', 'warga.%@bahumin.test')->firstOrFail();
        $joke = Joke::where('user_id', $dummy->id)->firstOrFail();

        $this->assertSame('published', $joke->status);
        $this->getJson('/api/jokes')->assertOk()->assertJsonFragment(['setup' => $joke->setup]);
    }

    public function test_admin_can_manage_user_role_but_cannot_delete_own_account(): void
    {
        $admin = $this->makeUser(true);
        $user = $this->makeUser();

        $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => $user->name,
            'is_admin' => '1',
        ])->assertRedirect('/admin/users');

        $this->assertTrue($user->fresh()->is_admin);

        $this->actingAs($admin)->delete("/admin/users/{$admin->id}")
            ->assertForbidden();
        $this->assertNotNull($admin->fresh());
    }

    public function test_admin_can_update_joke_and_manage_category(): void
    {
        $admin = $this->makeUser(true);
        $category = Category::create(['name' => 'Kantor', 'slug' => 'kantor', 'is_active' => true]);
        $joke = Joke::create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
            'setup' => 'Pertanyaan lama untuk diperiksa',
            'punchline' => 'Jawaban lama',
            'status' => 'draft',
        ]);

        $this->actingAs($admin)->put("/admin/jokes/{$joke->id}", [
            'category_id' => $category->id,
            'setup' => 'Pertanyaan baru untuk etalase',
            'punchline' => 'Jawaban baru',
            'status' => 'published',
        ])->assertRedirect('/admin/jokes');
        $this->assertDatabaseHas('jokes', ['id' => $joke->id, 'setup' => 'Pertanyaan baru untuk etalase', 'status' => 'published']);

        $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Dapur Warkop',
            'description' => 'Tebakan seputar dapur',
        ])->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Dapur Warkop', 'slug' => 'dapur-warkop']);
    }

    public function test_draft_joke_is_hidden_from_guest_but_visible_to_admin(): void
    {
        $admin = $this->makeUser(true);
        $joke = Joke::create([
            'user_id' => $admin->id,
            'setup' => 'Draft yang tidak boleh terlihat tamu',
            'punchline' => 'Masih disimpan',
            'status' => 'draft',
        ]);

        $this->get("/jokes/{$joke->id}")->assertNotFound();
        $this->actingAs($admin)->get("/jokes/{$joke->id}")->assertOk();
    }

    public function test_admin_seeder_creates_superadmin_who_can_login_with_username(): void
    {
        $this->seed(AdminSeeder::class);

        $this->assertDatabaseHas('users', [
            'username' => 'superadmin',
            'email' => 'admin@admin.com',
            'is_admin' => true,
        ]);

        $this->post('/admin/login', [
            'login' => 'superadmin',
            'password' => '12341234',
        ])->assertRedirect('/admin');
    }

    public function test_home_uses_bahumin_name_and_seo_metadata(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Bapak Humor Indonesia')
            ->assertSee('<meta name="description"', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('property="og:description"', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml');
    }

    private function makeUser(bool $admin = false): User
    {
        static $sequence = 0;
        $sequence++;

        return User::create([
            'name' => $admin ? 'Admin Bahumin' : 'Warga Bahumin',
            'username' => 'warga_'.$sequence,
            'email' => 'warga_'.$sequence.'@example.test',
            'password' => 'password',
            'is_admin' => $admin,
            'total_xp' => 0,
        ]);
    }
}

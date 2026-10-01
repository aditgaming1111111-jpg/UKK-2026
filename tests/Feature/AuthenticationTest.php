<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_login_page_is_displayed_in_indonesian(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk ke akun Anda')
            ->assertSee('Kata sandi');
    }

    public function test_admin_can_log_in_and_is_redirected_to_admin_destination(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_active_guru_can_log_in_and_is_redirected_to_guru_destination(): void
    {
        $guruUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($guruUser)->create();

        $response = $this->post(route('login.store'), [
            'email' => $guruUser->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('guru.dashboard'));
        $this->assertAuthenticatedAs($guruUser);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout_ends_the_authenticated_session(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_internal_routes_redirect_guests_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('guru.dashboard'))->assertRedirect(route('login'));
    }

    public function test_users_cannot_open_the_other_roles_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);
        Guru::factory()->for($guruUser)->create();

        $this->actingAs($admin)->get(route('guru.dashboard'))->assertForbidden();
        $this->actingAs($guruUser)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_inactive_guru_cannot_log_in_or_continue_an_existing_session(): void
    {
        $guruUser = User::factory()->create(['role' => 'guru']);
        $guru = Guru::factory()->for($guruUser)->create(['status_aktif' => false]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => $guruUser->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        $guru->update(['status_aktif' => true]);
        $this->actingAs($guruUser)->get(route('guru.dashboard'))->assertOk();
        $guru->update(['status_aktif' => false]);

        $this->get(route('guru.dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();
    }
}

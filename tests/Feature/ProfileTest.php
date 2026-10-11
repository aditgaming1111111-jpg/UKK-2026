<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_admin_and_guru_can_view_profile_without_exposing_password_hash(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'admin-secret']);
        $teacherUser = User::factory()->create(['role' => 'guru', 'password' => 'teacher-secret']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create();

        $this->actingAs($admin)->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Admin')
            ->assertSee($admin->email)
            ->assertDontSee($admin->password);
        $this->actingAs($teacherUser)->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Guru')
            ->assertSee($teacher->nip)
            ->assertSee($teacher->email)
            ->assertDontSee($teacherUser->password);
    }

    public function test_teacher_can_update_own_account_but_unexpected_role_is_ignored_and_identity_stays_consistent(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Guru::factory()->for($teacherUser, 'user')->create();
        $previousIdentityEmail = $teacher->email;

        $this->actingAs($teacherUser)->put(route('profile.update'), [
            'nama' => 'Nama Guru Baru',
            'email' => 'TEACHER.NEW@example.test',
            'role' => 'admin',
            'status_aktif' => false,
            'password' => 'request-controlled-password',
        ])->assertRedirect(route('profile.edit'))->assertSessionHas('success');

        $teacherUser->refresh();
        $teacher->refresh();
        $this->assertSame('guru', $teacherUser->role);
        $this->assertSame('teacher.new@example.test', $teacherUser->email);
        $this->assertSame('Nama Guru Baru', $teacherUser->nama);
        $this->assertSame('Nama Guru Baru', $teacher->nama);
        $this->assertSame($previousIdentityEmail, $teacher->email);
        $this->assertTrue($teacher->status_aktif);
        $this->assertFalse(Hash::check('request-controlled-password', $teacherUser->password));
    }

    public function test_duplicate_profile_email_is_rejected(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $otherUser = User::factory()->create();

        $this->actingAs($user)->from(route('profile.edit'))->put(route('profile.update'), [
            'nama' => 'Nama Admin',
            'email' => $otherUser->email,
        ])->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors(['email' => 'Email sudah digunakan akun lain.']);

        $this->assertSame($user->email, $user->fresh()->email);
    }

    public function test_password_change_requires_current_password_and_confirmed_new_password(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'password' => 'current-secret']);
        $oldSessionId = session()->getId();

        $this->actingAs($user)->from(route('profile.edit'))->put(route('profile.password.update'), [
            'current_password' => 'incorrect-secret',
            'password' => 'new-password-2026',
            'password_confirmation' => 'new-password-2026',
        ])->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.']);

        $this->assertTrue(Hash::check('current-secret', $user->fresh()->password));

        $this->put(route('profile.password.update'), [
            'current_password' => 'current-secret',
            'password' => 'new-password-2026',
            'password_confirmation' => 'different-password',
        ])->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('current-secret', $user->fresh()->password));

        $this->put(route('profile.password.update'), [
            'current_password' => 'current-secret',
            'password' => 'new-password-2026',
            'password_confirmation' => 'new-password-2026',
        ])->assertRedirect(route('profile.edit'))->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-password-2026', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($oldSessionId, session()->getId());
    }

    public function test_guest_cannot_access_or_update_profile(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
        $this->put(route('profile.update'), ['nama' => 'Hack', 'email' => 'hack@example.test'])
            ->assertRedirect(route('login'));
        $this->put(route('profile.password.update'), [])->assertRedirect(route('login'));
    }
}

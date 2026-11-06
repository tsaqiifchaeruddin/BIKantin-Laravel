<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_bisa_login_dengan_username(): void
    {
        $user = User::factory()->create([
            'username' => 'budi_test',
            'password' => 'rahasia123',
        ]);

        $response = $this->post('/login', [
            'username' => 'budi_test',
            'password' => 'rahasia123',
        ]);

        $response->assertRedirect(route('siswa.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_admin_diarahkan_ke_dashboard_admin(): void
    {
        User::factory()->admin()->create([
            'username' => 'admin_test',
            'password' => 'rahasia123',
        ]);

        $this->post('/login', [
            'username' => 'admin_test',
            'password' => 'rahasia123',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_kasir_diarahkan_ke_dashboard_kasir(): void
    {
        User::factory()->kasir()->create([
            'username' => 'kasir_test',
            'password' => 'rahasia123',
        ]);

        $this->post('/login', [
            'username' => 'kasir_test',
            'password' => 'rahasia123',
        ])->assertRedirect(route('kasir.dashboard'));
    }

    public function test_password_salah_ditolak(): void
    {
        User::factory()->create([
            'username' => 'budi_test',
            'password' => 'benar123',
        ]);

        $this->from('/login')->post('/login', [
            'username' => 'budi_test',
            'password' => 'salah123',
        ])->assertRedirect('/login')->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_siswa_baru_bisa_daftar(): void
    {
        $this->post('/register', [
            'username' => 'siswa_baru',
            'kelas' => 'XI RPL 1',
            'no_wa' => '081234567890',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('siswa.dashboard'));

        $this->assertDatabaseHas('users', [
            'username' => 'siswa_baru',
            'role' => 'siswa',
        ]);
        $this->assertAuthenticated();
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('siswa.dashboard'))->assertRedirect(route('login'));
    }
}

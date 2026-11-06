<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_tidak_bisa_membuka_halaman_admin(): void
    {
        $siswa = User::factory()->create();

        $this->actingAs($siswa)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($siswa)->get(route('admin.menu.index'))->assertForbidden();
    }

    public function test_siswa_tidak_bisa_membuka_halaman_kasir(): void
    {
        $siswa = User::factory()->create();

        $this->actingAs($siswa)->get(route('kasir.dashboard'))->assertForbidden();
    }

    public function test_kasir_tidak_bisa_membuka_halaman_admin(): void
    {
        $kasir = User::factory()->kasir()->create();

        $this->actingAs($kasir)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_bisa_membuka_dashboard_dan_kasir(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('kasir.dashboard'))->assertOk();
    }

    public function test_kasir_bisa_membuka_dashboard_kasir(): void
    {
        $kasir = User::factory()->kasir()->create();

        $this->actingAs($kasir)->get(route('kasir.dashboard'))->assertOk();
    }
}

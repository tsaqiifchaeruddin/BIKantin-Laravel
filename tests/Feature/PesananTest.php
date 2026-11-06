<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesananTest extends TestCase
{
    use RefreshDatabase;

    private function menu(array $atribut = []): Menu
    {
        return Menu::create($atribut + [
            'nama_menu' => 'Nasi Goreng',
            'harga' => 15000,
            'kategori' => 'Makanan',
            'tersedia' => true,
        ]);
    }

    public function test_siswa_bisa_membuat_pesanan_dan_total_dihitung_server(): void
    {
        $siswa = User::factory()->create();
        $menu = $this->menu();

        $this->actingAs($siswa)
            ->post(route('siswa.pesanan.store'), [
                'menu_id' => $menu->id,
                'jumlah' => 3,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('pesanans', [
            'user_id' => $siswa->id,
            'menu_id' => $menu->id,
            'jumlah' => 3,
            'total_harga' => 45000,
            'status' => Pesanan::STATUS_AWAL,
            'status_bayar' => Pesanan::BELUM_LUNAS,
        ]);
    }

    public function test_menu_habis_tidak_bisa_dipesan(): void
    {
        $siswa = User::factory()->create();
        $menu = $this->menu(['tersedia' => false]);

        $this->actingAs($siswa)
            ->from(route('siswa.dashboard'))
            ->post(route('siswa.pesanan.store'), [
                'menu_id' => $menu->id,
                'jumlah' => 1,
            ])
            ->assertRedirect(route('siswa.dashboard'))
            ->assertSessionHasErrors('menu_id');

        $this->assertDatabaseCount('pesanans', 0);
    }

    public function test_jumlah_pesanan_dibatasi(): void
    {
        $siswa = User::factory()->create();
        $menu = $this->menu();

        $this->actingAs($siswa)
            ->post(route('siswa.pesanan.store'), [
                'menu_id' => $menu->id,
                'jumlah' => 21,
            ])
            ->assertSessionHasErrors('jumlah');
    }

    public function test_endpoint_status_hanya_mengembalikan_pesanan_milik_siswa(): void
    {
        $budi = User::factory()->create();
        $siti = User::factory()->create();
        $menu = $this->menu();

        Pesanan::create([
            'user_id' => $budi->id,
            'menu_id' => $menu->id,
            'jumlah' => 1,
            'total_harga' => 15000,
        ]);
        Pesanan::create([
            'user_id' => $siti->id,
            'menu_id' => $menu->id,
            'jumlah' => 2,
            'total_harga' => 30000,
        ]);

        $this->actingAs($budi)
            ->getJson(route('siswa.pesanan.status'))
            ->assertOk()
            ->assertJsonCount(1, 'pesanans')
            ->assertJsonPath('pesanans.0.status', Pesanan::STATUS_AWAL);
    }
}

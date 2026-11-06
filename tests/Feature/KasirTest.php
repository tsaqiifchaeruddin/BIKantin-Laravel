<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirTest extends TestCase
{
    use RefreshDatabase;

    private function pesanan(User $siswa): Pesanan
    {
        $menu = Menu::create([
            'nama_menu' => 'Nasi Goreng',
            'harga' => 15000,
            'kategori' => 'Makanan',
        ]);

        return Pesanan::create([
            'user_id' => $siswa->id,
            'menu_id' => $menu->id,
            'jumlah' => 2,
            'total_harga' => 30000,
        ]);
    }

    public function test_kasir_bisa_memvalidasi_pembayaran(): void
    {
        $kasir = User::factory()->kasir()->create();
        $pesanan = $this->pesanan(User::factory()->create());

        $this->actingAs($kasir)
            ->patch(route('kasir.pesanan.lunas', $pesanan))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pesanans', [
            'id' => $pesanan->id,
            'status_bayar' => Pesanan::LUNAS,
        ]);
    }

    public function test_admin_juga_bisa_memvalidasi_pembayaran(): void
    {
        $admin = User::factory()->admin()->create();
        $pesanan = $this->pesanan(User::factory()->create());

        $this->actingAs($admin)
            ->patch(route('kasir.pesanan.lunas', $pesanan))
            ->assertRedirect();

        $this->assertDatabaseHas('pesanans', [
            'id' => $pesanan->id,
            'status_bayar' => Pesanan::LUNAS,
        ]);
    }

    public function test_siswa_tidak_bisa_memvalidasi_pembayaran(): void
    {
        $siswa = User::factory()->create();
        $pesanan = $this->pesanan($siswa);

        $this->actingAs($siswa)
            ->patch(route('kasir.pesanan.lunas', $pesanan))
            ->assertForbidden();

        $this->assertDatabaseHas('pesanans', [
            'id' => $pesanan->id,
            'status_bayar' => Pesanan::BELUM_LUNAS,
        ]);
    }

    public function test_struk_menampilkan_data_pesanan(): void
    {
        $kasir = User::factory()->kasir()->create();
        $pesanan = $this->pesanan(User::factory()->create(['username' => 'budi_siswa']));

        $this->actingAs($kasir)
            ->get(route('kasir.pesanan.struk', $pesanan))
            ->assertOk()
            ->assertSee('BI-KANTIN')
            ->assertSee('budi_siswa')
            ->assertSee('BELUM LUNAS');
    }

    public function test_admin_bisa_mengubah_status_pesanan(): void
    {
        $admin = User::factory()->admin()->create();
        $pesanan = $this->pesanan(User::factory()->create());

        $this->actingAs($admin)
            ->patch(route('admin.pesanan.status', $pesanan), [
                'status' => 'Sedang Dimasak',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('pesanans', [
            'id' => $pesanan->id,
            'status' => 'Sedang Dimasak',
        ]);
    }

    public function test_status_pesanan_tidak_boleh_sembarangan(): void
    {
        $admin = User::factory()->admin()->create();
        $pesanan = $this->pesanan(User::factory()->create());

        $this->actingAs($admin)
            ->patch(route('admin.pesanan.status', $pesanan), [
                'status' => 'Status Karangan',
            ])
            ->assertSessionHasErrors('status');
    }
}

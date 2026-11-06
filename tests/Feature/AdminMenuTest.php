<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_menambah_menu(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.menu.store'), [
                'nama_menu' => 'Bakso Malang',
                'harga' => 13000,
                'kategori' => 'Makanan',
                'deskripsi' => 'Bakso sapi dengan mie.',
                'tersedia' => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('menus', [
            'nama_menu' => 'Bakso Malang',
            'harga' => 13000,
            'tersedia' => true,
        ]);
    }

    public function test_admin_bisa_mengubah_menu(): void
    {
        $admin = User::factory()->admin()->create();
        $menu = Menu::create([
            'nama_menu' => 'Es Teh',
            'harga' => 4000,
            'kategori' => 'Minuman',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.menu.update', $menu), [
                'nama_menu' => 'Es Teh Jumbo',
                'harga' => 6000,
                'kategori' => 'Minuman',
                'tersedia' => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'nama_menu' => 'Es Teh Jumbo',
            'harga' => 6000,
        ]);
    }

    public function test_admin_bisa_menandai_menu_habis(): void
    {
        $admin = User::factory()->admin()->create();
        $menu = Menu::create([
            'nama_menu' => 'Pisang Goreng',
            'harga' => 5000,
            'kategori' => 'Snack',
            'tersedia' => true,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.menu.toggle', $menu))
            ->assertRedirect();

        $this->assertDatabaseHas('menus', ['id' => $menu->id, 'tersedia' => false]);
    }

    public function test_menu_tanpa_riwayat_pesanan_bisa_dihapus(): void
    {
        $admin = User::factory()->admin()->create();
        $menu = Menu::create([
            'nama_menu' => 'Roti Bakar',
            'harga' => 8000,
            'kategori' => 'Snack',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.menu.destroy', $menu))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    public function test_menu_dengan_riwayat_pesanan_tidak_bisa_dihapus(): void
    {
        $admin = User::factory()->admin()->create();
        $siswa = User::factory()->create();
        $menu = Menu::create([
            'nama_menu' => 'Mie Ayam',
            'harga' => 12000,
            'kategori' => 'Makanan',
        ]);

        Pesanan::create([
            'user_id' => $siswa->id,
            'menu_id' => $menu->id,
            'jumlah' => 1,
            'total_harga' => 12000,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.menu.destroy', $menu))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('menus', ['id' => $menu->id]);
    }

    public function test_harga_menu_harus_masuk_akal(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.menu.store'), [
                'nama_menu' => 'Menu Gratis',
                'harga' => 0,
                'kategori' => 'Makanan',
            ])
            ->assertSessionHasErrors('harga');
    }
}

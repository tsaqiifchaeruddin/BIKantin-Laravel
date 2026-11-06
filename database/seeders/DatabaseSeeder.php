<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* ------------------------------------------------------------ akun */
        User::create([
            'username' => 'admin_kantin',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'kasir_kantin',
            'password' => 'kasir123',
            'role' => 'kasir',
        ]);

        $budi = User::create([
            'username' => 'budi_siswa',
            'password' => '123',
            'role' => 'siswa',
            'kelas' => 'XI RPL 1',
            'no_wa' => '081234567890',
        ]);

        $siti = User::create([
            'username' => 'siti_siswa',
            'password' => '123',
            'role' => 'siswa',
            'kelas' => 'XI RPL 2',
            'no_wa' => '081298765432',
        ]);

        /* ------------------------------------------------------------ menu */
        $menus = [
            ['nama_menu' => 'Nasi Goreng Spesial', 'harga' => 15000, 'kategori' => 'Makanan', 'deskripsi' => 'Nasi goreng dengan telur, ayam, dan kerupuk.'],
            ['nama_menu' => 'Mie Ayam Bakso', 'harga' => 12000, 'kategori' => 'Makanan', 'deskripsi' => 'Mie ayam gurih dengan bakso sapi.'],
            ['nama_menu' => 'Ayam Geprek + Nasi', 'harga' => 14000, 'kategori' => 'Makanan', 'deskripsi' => 'Ayam crispy sambal bawang, plus nasi hangat.'],
            ['nama_menu' => 'Roti Bakar Cokelat', 'harga' => 8000, 'kategori' => 'Snack', 'deskripsi' => 'Roti bakar isi cokelat keju.'],
            ['nama_menu' => 'Pisang Goreng', 'harga' => 5000, 'kategori' => 'Snack', 'deskripsi' => 'Pisang goreng renyah, isi 3 potong.'],
            ['nama_menu' => 'Es Teh Manis', 'harga' => 4000, 'kategori' => 'Minuman', 'deskripsi' => 'Teh manis dingin, segar.'],
            ['nama_menu' => 'Air Mineral', 'harga' => 3000, 'kategori' => 'Minuman', 'deskripsi' => 'Air mineral botol 600 ml.'],
        ];

        foreach ($menus as $item) {
            Menu::create($item + ['tersedia' => true]);
        }

        Menu::create([
            'nama_menu' => 'Es Jeruk Peras',
            'harga' => 5000,
            'kategori' => 'Minuman',
            'deskripsi' => 'Jeruk peras asli.',
            'tersedia' => false,
        ]);

        /* --------------------------------------------------- contoh pesanan */
        $nasiGoreng = Menu::where('nama_menu', 'Nasi Goreng Spesial')->first();
        $esTeh = Menu::where('nama_menu', 'Es Teh Manis')->first();

        Pesanan::create([
            'user_id' => $budi->id,
            'menu_id' => $nasiGoreng->id,
            'jumlah' => 2,
            'total_harga' => $nasiGoreng->harga * 2,
            'status' => Pesanan::STATUS_AWAL,
            'status_bayar' => Pesanan::BELUM_LUNAS,
            'created_at' => now()->subMinutes(25),
            'updated_at' => now()->subMinutes(25),
        ]);

        Pesanan::create([
            'user_id' => $siti->id,
            'menu_id' => $esTeh->id,
            'jumlah' => 3,
            'total_harga' => $esTeh->harga * 3,
            'status' => 'Siap Diambil',
            'status_bayar' => Pesanan::LUNAS,
            'created_at' => now()->subMinutes(60),
            'updated_at' => now()->subMinutes(10),
        ]);
    }
}

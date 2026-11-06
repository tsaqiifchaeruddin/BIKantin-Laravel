<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $kategori = $request->query('kategori');

        $menus = Menu::query()
            ->where('tersedia', true)
            ->when($kategori, fn ($query) => $query->where('kategori', $kategori))
            ->orderBy('kategori')
            ->orderBy('nama_menu')
            ->get();

        $pesanans = Pesanan::query()
            ->with('menu')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        $kategoris = Menu::query()
            ->where('tersedia', true)
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('siswa.dashboard', [
            'menus' => $menus,
            'pesanans' => $pesanans,
            'kategoris' => $kategoris,
            'kategoriAktif' => $kategori,
            'pesananAktif' => $pesanans->whereIn('status', array_slice(Pesanan::STATUS, 0, 3))->count(),
        ]);
    }
}

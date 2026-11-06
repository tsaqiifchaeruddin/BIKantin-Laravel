<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'menus' => Menu::query()
                ->where('tersedia', true)
                ->orderBy('kategori')
                ->orderBy('nama_menu')
                ->get(),
            'totalMenu' => Menu::query()->where('tersedia', true)->count(),
            'totalPesanan' => Pesanan::query()->count(),
        ]);
    }
}

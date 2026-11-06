<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hariIni = today();

        return view('admin.dashboard', [
            'totalMenu' => Menu::query()->count(),
            'menuTersedia' => Menu::query()->where('tersedia', true)->count(),
            'pesananHariIni' => Pesanan::query()->whereDate('created_at', $hariIni)->count(),
            'pesananAktif' => Pesanan::query()->where('status', '!=', 'Selesai')->count(),
            'pendapatanHariIni' => Pesanan::query()
                ->whereDate('created_at', $hariIni)
                ->where('status_bayar', Pesanan::LUNAS)
                ->sum('total_harga'),
            'totalSiswa' => User::query()->where('role', 'siswa')->count(),
            'pesanans' => Pesanan::query()->with(['user', 'menu'])->latest()->limit(6)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'belum');
        $cari = $request->query('cari');

        $pesanans = Pesanan::query()
            ->with(['user', 'menu'])
            ->when($filter === 'belum', fn ($query) => $query->where('status_bayar', Pesanan::BELUM_LUNAS))
            ->when($filter === 'lunas', fn ($query) => $query->where('status_bayar', Pesanan::LUNAS))
            ->when($cari, function ($query) use ($cari) {
                $id = (int) ltrim($cari, '#');

                $query->where(function ($q) use ($cari, $id) {
                    $q->whereHas('user', fn ($u) => $u->where('username', 'like', '%'.$cari.'%'));

                    if ($id > 0) {
                        $q->orWhere('id', $id);
                    }
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kasir.dashboard', [
            'pesanans' => $pesanans,
            'filter' => $filter,
            'cari' => $cari,
            'jumlahBelumLunas' => Pesanan::query()->where('status_bayar', Pesanan::BELUM_LUNAS)->count(),
            'pendapatanHariIni' => Pesanan::query()
                ->whereDate('created_at', today())
                ->where('status_bayar', Pesanan::LUNAS)
                ->sum('total_harga'),
            'transaksiHariIni' => Pesanan::query()
                ->whereDate('created_at', today())
                ->where('status_bayar', Pesanan::LUNAS)
                ->count(),
        ]);
    }
}

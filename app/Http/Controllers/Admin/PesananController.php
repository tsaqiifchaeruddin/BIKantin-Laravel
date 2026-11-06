<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $cari = $request->query('cari');

        $pesanans = Pesanan::query()
            ->with(['user', 'menu'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($cari, fn ($query) => $query->where(function ($q) use ($cari) {
                $q->whereHas('user', fn ($u) => $u->where('username', 'like', '%'.$cari.'%'))
                    ->orWhereHas('menu', fn ($m) => $m->where('nama_menu', 'like', '%'.$cari.'%'));
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pesanan', [
            'pesanans' => $pesanans,
            'status' => $status,
            'cari' => $cari,
            'daftarStatus' => Pesanan::STATUS,
        ]);
    }

    public function updateStatus(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Pesanan::STATUS)],
        ]);

        $pesanan->update(['status' => $data['status']]);

        return back()->with('success', 'Pesanan #'.$pesanan->id.' sekarang berstatus "'.$data['status'].'".');
    }
}

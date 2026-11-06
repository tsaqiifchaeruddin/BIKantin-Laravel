<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function lunas(Pesanan $pesanan): RedirectResponse
    {
        if ($pesanan->sudahLunas()) {
            return back()->with('error', 'Pesanan #'.$pesanan->id.' sudah berstatus Lunas.');
        }

        $pesanan->update(['status_bayar' => Pesanan::LUNAS]);

        return back()->with('success', 'Pembayaran pesanan #'.$pesanan->id.' ('.$pesanan->user->username.') divalidasi: Lunas.');
    }

    public function struk(Pesanan $pesanan): View
    {
        $pesanan->load(['user', 'menu']);

        return view('kasir.struk', [
            'pesanan' => $pesanan,
        ]);
    }
}

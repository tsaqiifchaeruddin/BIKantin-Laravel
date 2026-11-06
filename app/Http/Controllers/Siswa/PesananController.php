<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PesananController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'menu_id' => ['required', 'integer', 'exists:menus,id'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:20'],
            'catatan' => ['nullable', 'string', 'max:200'],
        ]);

        $menu = Menu::findOrFail($data['menu_id']);

        if (! $menu->tersedia) {
            throw ValidationException::withMessages([
                'menu_id' => 'Menu ini sedang habis. Silakan pilih menu lain.',
            ]);
        }

        Pesanan::create([
            'user_id' => $request->user()->id,
            'menu_id' => $menu->id,
            'jumlah' => $data['jumlah'],
            'total_harga' => $menu->harga * $data['jumlah'],
            'status' => Pesanan::STATUS_AWAL,
            'status_bayar' => Pesanan::BELUM_LUNAS,
            'catatan' => $data['catatan'] ?? null,
        ]);

        return back()->with('success', 'Pesanan '.$menu->nama_menu.' ('.$data['jumlah'].'x) masuk. Bayar di kasir, ya!');
    }

    /** Data status untuk pembaruan otomatis di halaman siswa. */
    public function status(Request $request): JsonResponse
    {
        $pesanans = Pesanan::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get(['id', 'status', 'status_bayar', 'updated_at']);

        return response()->json([
            'waktu' => now()->format('H:i:s'),
            'pesanans' => $pesanans,
        ]);
    }
}

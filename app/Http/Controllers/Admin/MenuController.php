<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $cari = $request->query('cari');

        $menus = Menu::query()
            ->when($cari, fn ($query) => $query->where(function ($q) use ($cari) {
                $q->where('nama_menu', 'like', '%'.$cari.'%')
                    ->orWhere('kategori', 'like', '%'.$cari.'%');
            }))
            ->orderBy('kategori')
            ->orderBy('nama_menu')
            ->paginate(10)
            ->withQueryString();

        return view('admin.menu', [
            'menus' => $menus,
            'cari' => $cari,
            'kategoris' => Menu::KATEGORI,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Menu::create($this->validated($request));

        return back()->with('success', 'Menu baru berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $menu->update($this->validated($request));

        return back()->with('success', 'Menu "'.$menu->nama_menu.'" berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        if ($menu->pesanans()->exists()) {
            return back()->with('error', 'Menu "'.$menu->nama_menu.'" sudah punya riwayat pesanan, jadi tidak bisa dihapus. Tandai saja sebagai Habis.');
        }

        $nama = $menu->nama_menu;
        $menu->delete();

        return back()->with('success', 'Menu "'.$nama.'" dihapus.');
    }

    public function toggle(Menu $menu): RedirectResponse
    {
        $menu->update(['tersedia' => ! $menu->tersedia]);

        return back()->with('success', 'Menu "'.$menu->nama_menu.'" sekarang '.(($menu->tersedia) ? 'tersedia' : 'habis').'.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_menu' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'integer', 'min:500', 'max:1000000'],
            'kategori' => ['required', Rule::in(Menu::KATEGORI)],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'tersedia' => ['nullable', 'boolean'],
        ]) + ['tersedia' => $request->boolean('tersedia')];
    }
}

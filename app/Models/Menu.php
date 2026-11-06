<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    public const KATEGORI = ['Makanan', 'Minuman', 'Snack'];

    protected $fillable = [
        'nama_menu',
        'harga',
        'kategori',
        'deskripsi',
        'tersedia',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'tersedia' => 'boolean',
        ];
    }

    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }
}

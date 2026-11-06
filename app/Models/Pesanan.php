<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesanan extends Model
{
    public const STATUS = [
        'Menunggu Konfirmasi',
        'Sedang Dimasak',
        'Siap Diambil',
        'Selesai',
    ];

    public const STATUS_AWAL = 'Menunggu Konfirmasi';
    public const BELUM_LUNAS = 'Belum Lunas';
    public const LUNAS = 'Lunas';

    protected $fillable = [
        'user_id',
        'menu_id',
        'jumlah',
        'total_harga',
        'status',
        'status_bayar',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'total_harga' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function sudahLunas(): bool
    {
        return $this->status_bayar === self::LUNAS;
    }

    /** Langkah status berikutnya, null kalau sudah selesai. */
    public function statusBerikutnya(): ?string
    {
        $index = array_search($this->status, self::STATUS, true);

        return $index === false ? null : (self::STATUS[$index + 1] ?? null);
    }
}

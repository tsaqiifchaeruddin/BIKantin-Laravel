<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menus')->restrictOnDelete();
            $table->unsignedInteger('jumlah')->default(1);
            $table->integer('total_harga');
            $table->string('status', 30)->default('Menunggu Konfirmasi');
            $table->string('status_bayar', 20)->default('Belum Lunas');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['status', 'status_bayar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};

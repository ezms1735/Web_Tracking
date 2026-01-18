<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('pengguna')->onDelete('cascade');
            $table->enum('status_pengiriman', ['belum_dikirim', 'proses', 'selesai']);
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};

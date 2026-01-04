<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pengguna')->onDelete('cascade');
            $table->integer('jumlah_pesanan');
            $table->enum('status_pesanan', ['menunggu', 'proses', 'selesai']);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};

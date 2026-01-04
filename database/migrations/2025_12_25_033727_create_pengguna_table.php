<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('email', 100)->unique();
            $table->string('nomor_telepon', 20);
            $table->text('alamat')->nullable();
            $table->string('password');
            $table->enum('peran', ['admin', 'driver', 'pelanggan']);
            $table->enum('status', ['aktif', 'nonaktif']);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};

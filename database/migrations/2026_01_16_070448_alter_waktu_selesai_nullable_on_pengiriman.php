<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->dateTime('waktu_selesai')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->dateTime('waktu_selesai')->nullable(false)->change();
        });
    }
};

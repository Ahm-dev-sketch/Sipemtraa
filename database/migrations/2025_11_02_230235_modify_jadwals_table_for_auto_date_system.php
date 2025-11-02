<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            // Tambah kolom untuk sistem auto-date
            $table->boolean('is_active')->default(true)->after('harga'); // Jadwal aktif atau tidak
            $table->enum('day_offset', ['0', '1', '2', '3', '4', '5', '6', '7'])->default('0')->after('is_active');
            // 0 = hari ini, 1 = besok, 2 = lusa, dst (max 7 hari ke depan)
            $table->text('notes')->nullable()->after('day_offset'); // Catatan jadwal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'day_offset', 'notes']);
        });
    }
};

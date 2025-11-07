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
        Schema::table('bookings', function (Blueprint $table) {
            // Hapus kolom jadwal_tanggal
            $table->dropColumn('jadwal_tanggal');

            // Tambah kolom jadwal_hari_keberangkatan
            $table->enum('jadwal_hari_keberangkatan', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])->after('seat_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Hapus kolom jadwal_hari_keberangkatan
            $table->dropColumn('jadwal_hari_keberangkatan');

            // Kembalikan kolom jadwal_tanggal
            $table->date('jadwal_tanggal')->after('seat_number');
        });
    }
};

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
            // Hapus kolom tanggal dan day_offset
            $table->dropColumn(['tanggal', 'day_offset']);

            // Tambah kolom hari_keberangkatan
            $table->enum('hari_keberangkatan', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])->after('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            // Hapus kolom hari_keberangkatan
            $table->dropColumn('hari_keberangkatan');

            // Kembalikan kolom tanggal dan day_offset
            $table->date('tanggal')->after('harga');
            $table->enum('day_offset', ['0', '1', '2', '3', '4', '5', '6', '7'])->default('0')->after('tanggal');
        });
    }
};

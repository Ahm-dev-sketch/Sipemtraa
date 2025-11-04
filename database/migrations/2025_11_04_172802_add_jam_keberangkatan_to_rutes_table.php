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
        Schema::table('rutes', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('rutes', 'jam_keberangkatan')) {
                $table->time('jam_keberangkatan')->nullable()->after('jarak_estimasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutes', function (Blueprint $table) {
            if (Schema::hasColumn('rutes', 'jam_keberangkatan')) {
                $table->dropColumn('jam_keberangkatan');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add database indexes for performance optimization
     */
    public function up(): void
    {
        // Helper function to create index safely
        $createIndex = function ($table, $columns, $indexName) {
            try {
                if (is_array($columns)) {
                    $cols = implode(', ', $columns);
                } else {
                    $cols = $columns;
                }
                DB::statement("CREATE INDEX {$indexName} ON {$table}({$cols})");
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        };

        // Bookings indexes
        $createIndex('bookings', 'user_id', 'idx_bookings_user_id');
        $createIndex('bookings', 'created_at', 'idx_bookings_created_at');

        // Jadwals indexes
        $createIndex('jadwals', 'tanggal', 'idx_jadwals_tanggal');
        $createIndex('jadwals', 'is_active', 'idx_jadwals_is_active');
        $createIndex('jadwals', 'rute_id', 'idx_jadwals_rute_id');
        $createIndex('jadwals', 'mobil_id', 'idx_jadwals_mobil_id');
        $createIndex('jadwals', ['tanggal', 'is_active'], 'idx_jadwals_tanggal_active');

        // Rutes indexes
        $createIndex('rutes', 'kota_asal', 'idx_rutes_kota_asal');
        $createIndex('rutes', 'kota_tujuan', 'idx_rutes_kota_tujuan');
        $createIndex('rutes', ['kota_asal', 'kota_tujuan'], 'idx_rutes_route');

        // Users indexes
        $createIndex('users', 'whatsapp_number', 'idx_users_whatsapp');
        $createIndex('users', 'role', 'idx_users_role');

        // OTP Tokens indexes
        $createIndex('otp_tokens', 'whatsapp_number', 'idx_otp_whatsapp');
        $createIndex('otp_tokens', 'expires_at', 'idx_otp_expires');
        $createIndex('otp_tokens', ['whatsapp_number', 'otp_code'], 'idx_otp_lookup');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Helper function to drop index safely
        $dropIndex = function ($table, $indexName) {
            try {
                DB::statement("DROP INDEX {$indexName} ON {$table}");
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        };

        // Drop bookings indexes
        $dropIndex('bookings', 'idx_bookings_user_id');
        $dropIndex('bookings', 'idx_bookings_created_at');

        // Drop jadwals indexes
        $dropIndex('jadwals', 'idx_jadwals_tanggal');
        $dropIndex('jadwals', 'idx_jadwals_is_active');
        $dropIndex('jadwals', 'idx_jadwals_rute_id');
        $dropIndex('jadwals', 'idx_jadwals_mobil_id');
        $dropIndex('jadwals', 'idx_jadwals_tanggal_active');

        // Drop rutes indexes
        $dropIndex('rutes', 'idx_rutes_kota_asal');
        $dropIndex('rutes', 'idx_rutes_kota_tujuan');
        $dropIndex('rutes', 'idx_rutes_route');

        // Drop users indexes
        $dropIndex('users', 'idx_users_whatsapp');
        $dropIndex('users', 'idx_users_role');

        // Drop otp_tokens indexes
        $dropIndex('otp_tokens', 'idx_otp_whatsapp');
        $dropIndex('otp_tokens', 'idx_otp_expires');
        $dropIndex('otp_tokens', 'idx_otp_lookup');
    }
};

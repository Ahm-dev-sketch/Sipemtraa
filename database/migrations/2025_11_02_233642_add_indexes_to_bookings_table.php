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
            // Add indexes for frequently queried columns
            $table->index(['status', 'created_at'], 'idx_status_created_at');
            $table->index('payment_status', 'idx_payment_status');
            $table->index('jadwal_id', 'idx_jadwal_id');
            $table->index(['jadwal_id', 'seat_number'], 'idx_jadwal_seat');
            $table->index('ticket_number', 'idx_ticket_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex('idx_status_created_at');
            $table->dropIndex('idx_payment_status');
            $table->dropIndex('idx_jadwal_id');
            $table->dropIndex('idx_jadwal_seat');
            $table->dropIndex('idx_ticket_number');
        });
    }
};

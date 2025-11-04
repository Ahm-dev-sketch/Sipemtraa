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
        // Add soft deletes to rutes table
        if (!Schema::hasColumn('rutes', 'deleted_at')) {
            Schema::table('rutes', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add soft deletes to mobils table
        if (!Schema::hasColumn('mobils', 'deleted_at')) {
            Schema::table('mobils', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add soft deletes to jadwals table
        if (!Schema::hasColumn('jadwals', 'deleted_at')) {
            Schema::table('jadwals', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rutes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('mobils', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};

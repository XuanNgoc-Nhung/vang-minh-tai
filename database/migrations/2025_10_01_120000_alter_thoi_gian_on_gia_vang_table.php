<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure updates to rows do not auto-change 'thoi_gian'.
        // Switch to DATETIME (no ON UPDATE behavior in MySQL).
        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE `gia_vang` MODIFY `thoi_gian` DATETIME NOT NULL');
        } else {
            // For other drivers (e.g., sqlite), leave as-is to avoid breaking migrations.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to TIMESTAMP (behavior may vary by MySQL version).
        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE `gia_vang` MODIFY `thoi_gian` TIMESTAMP NOT NULL');
        } else {
            // No-op for non-MySQL drivers.
        }
    }
};



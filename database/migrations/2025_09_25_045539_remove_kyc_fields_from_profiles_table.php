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
        Schema::table('profiles', function (Blueprint $table) {
            // Remove KYC fields that are no longer needed
            $table->dropColumn([
                'ho_ten',
                'so_dien_thoai', 
                'so_cccd_cmnd',
                'trang_thai_kyc'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Add back KYC fields if needed to rollback
            $table->string('ho_ten')->nullable()->after('user_id');
            $table->string('so_dien_thoai')->nullable()->after('ho_ten');
            $table->string('so_cccd_cmnd')->nullable()->after('so_dien_thoai');
            $table->enum('trang_thai_kyc', ['pending', 'verified', 'rejected'])->default('pending')->after('anh_chan_dung');
        });
    }
};
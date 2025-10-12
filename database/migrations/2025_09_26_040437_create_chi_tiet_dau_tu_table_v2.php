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
        Schema::create('chi_tiet_dau_tu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dau_tu_id')->constrained('dau_tu')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_pham_dau_tu')->onDelete('cascade');
            $table->unsignedInteger('chu_ky_thu')->comment('Chu kỳ thứ mấy (1, 2, 3...)');
            $table->decimal('tien_goc', 15, 2)->default(0)->comment('Số tiền gốc của chu kỳ này');
            $table->decimal('tien_lai', 15, 2)->default(0)->comment('Số tiền lãi của chu kỳ này');
            $table->decimal('tong_tien', 15, 2)->default(0)->comment('Tổng tiền gốc + lãi');
            $table->timestamp('thoi_gian_nhan')->nullable()->comment('Thời gian nhận tiền');
            $table->tinyInteger('trang_thai')->default(0)->comment('0: Chưa nhận, 1: Đã nhận, 2: Quá hạn');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['dau_tu_id', 'chu_ky_thu']);
            $table->index(['user_id', 'trang_thai']);
            $table->index('thoi_gian_nhan');
            $table->index('created_at');
            
            // Unique constraint để tránh trùng lặp chu kỳ
            $table->unique(['dau_tu_id', 'chu_ky_thu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_dau_tu');
    }
};
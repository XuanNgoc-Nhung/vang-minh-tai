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
        Schema::create('dau_tu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_pham_dau_tu')->onDelete('cascade');
            $table->unsignedInteger('so_chu_ky');
            $table->unsignedBigInteger('so_tien')->default(0);
            $table->decimal('hoa_hong', 10, 2)->default(0);
            $table->tinyInteger('trang_thai')->default(0)->comment('0: Chờ xử lý, 1: Đang hoạt động, 2: Hoàn thành, 3: Hủy bỏ');
            $table->timestamp('ngay_bat_dau')->nullable();
            $table->timestamp('ngay_ket_thuc')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'trang_thai']);
            $table->index(['san_pham_id', 'trang_thai']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dau_tu');
    }
};
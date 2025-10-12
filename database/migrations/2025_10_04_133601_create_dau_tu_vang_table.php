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
        Schema::create('dau_tu_vang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('id_vang')->constrained('san_pham_vang')->onDelete('cascade');
            $table->decimal('so_luong', 10, 3)->comment('Số lượng vàng (gram)');
            $table->timestamp('thoi_gian')->comment('Thời gian mua');
            $table->decimal('gia_mua', 15, 2)->comment('Giá mua');
            $table->text('ghi_chu')->nullable();
            $table->tinyInteger('trang_thai')->default(1)->comment('1: còn hàng, 0: đã bán');
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'trang_thai']);
            $table->index(['id_vang', 'trang_thai']);
            $table->index('thoi_gian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dau_tu_vang');
    }
};

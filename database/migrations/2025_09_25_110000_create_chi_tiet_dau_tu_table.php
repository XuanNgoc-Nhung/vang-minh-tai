<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chi_tiet_dau_tu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dau_tu_id')->constrained('dau_tu')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_pham_dau_tu')->onDelete('cascade');
            $table->decimal('tien_goc', 15, 2)->default(0);
            $table->decimal('hoa_hong', 15, 2)->default(0);
            $table->timestamp('thoi_gian_nhan')->nullable();
            $table->tinyInteger('trang_thai')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_dau_tu');
    }
};



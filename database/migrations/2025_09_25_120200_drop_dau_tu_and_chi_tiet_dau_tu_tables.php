<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop child table first to avoid foreign key constraint issues
        Schema::dropIfExists('chi_tiet_dau_tu');
        Schema::dropIfExists('dau_tu');
    }

    public function down(): void
    {
        // Recreate dau_tu table with latest known columns
        Schema::create('dau_tu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_pham_dau_tu')->onDelete('cascade');
            $table->unsignedInteger('so_chu_ky');
            $table->decimal('hoa_hong', 10, 2)->default(0);
            $table->tinyInteger('trang_thai')->default(0);
            $table->unsignedBigInteger('so_tien')->default(0);
            $table->timestamps();
        });

        // Recreate chi_tiet_dau_tu table
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
};



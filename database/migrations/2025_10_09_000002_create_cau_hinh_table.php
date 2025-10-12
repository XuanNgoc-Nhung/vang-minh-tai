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
        Schema::create('cau_hinh', function (Blueprint $table) {
            $table->id();
            $table->string('token_tele')->nullable();
            $table->string('id_tele')->nullable();
            $table->string('id_live_chat')->nullable();
            $table->string('link_facebook')->nullable();
            $table->string('ma_so_doanh_nghiep')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cau_hinh');
    }
};

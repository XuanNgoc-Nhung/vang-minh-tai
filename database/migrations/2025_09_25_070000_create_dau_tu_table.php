<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dau_tu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_pham_dau_tu')->onDelete('cascade');
            $table->unsignedInteger('so_chu_ky');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dau_tu');
    }
};



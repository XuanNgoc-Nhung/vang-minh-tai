<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('san_pham_dau_tu', function (Blueprint $table) {
            $table->dropColumn('so_luong_chu_ky');
        });
    }

    public function down(): void
    {
        Schema::table('san_pham_dau_tu', function (Blueprint $table) {
            $table->unsignedInteger('so_luong_chu_ky')->nullable();
        });
    }
};



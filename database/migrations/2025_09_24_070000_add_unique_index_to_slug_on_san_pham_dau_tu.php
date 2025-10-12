<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('san_pham_dau_tu', function (Blueprint $table) {
            // Add unique index to enforce unique slugs
            $table->unique('slug', 'san_pham_dau_tu_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('san_pham_dau_tu', function (Blueprint $table) {
            $table->dropUnique('san_pham_dau_tu_slug_unique');
        });
    }
};



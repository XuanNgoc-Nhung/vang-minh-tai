<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('san_pham_dau_tu', function (Blueprint $table) {
            $table->decimal('lai_suat', 8, 4)->nullable()->after('von_toi_da');
        });
    }

    public function down(): void
    {
        Schema::table('san_pham_dau_tu', function (Blueprint $table) {
            $table->dropColumn('lai_suat');
        });
    }
};



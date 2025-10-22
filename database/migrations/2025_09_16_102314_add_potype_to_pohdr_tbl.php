<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pohdr_tbl', function (Blueprint $table) {
            $table->enum('potype', ['Lokal', 'Import', 'Inventaris'])->nullable()->after('pono');
        });
    }

    public function down(): void
    {
        Schema::table('pohdr_tbl', function (Blueprint $table) {
            $table->dropColumn('potype');
        });
    }
};


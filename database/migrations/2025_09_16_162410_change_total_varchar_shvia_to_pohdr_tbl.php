<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pohdr_tbl', function (Blueprint $table) {
            $table->string('shvia', 10)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pohdr_tbl', function (Blueprint $table) {
            $table->string('shvia', 1)->change();
        });
    }
};

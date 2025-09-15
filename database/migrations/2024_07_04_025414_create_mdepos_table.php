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
        Schema::table('mdepos', function (Blueprint $table) {
            $table->string('depo')->primary();
            $table->string('name');
            $table->string('braco');
            $table->text('address')->nullable();
            $table->string('cont')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('faxno')->nullable();
            $table->string('npwp')->nullable();
            $table->string('pkp')->nullable();
            $table->string('tglsk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mdepos');
    }
};

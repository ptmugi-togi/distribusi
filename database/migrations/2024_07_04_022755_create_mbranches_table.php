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
        Schema::table('mbranches', function (Blueprint $table) {
            $table->string('braco')->primary();
            $table->string('brana');
            $table->string('conam');
            $table->text('address');
            $table->string('contactp');
            $table->string('phone');
            $table->string('faxno');
            $table->string('npwp');
            $table->date('tglsk');
            $table->string('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbranches');
    }
};

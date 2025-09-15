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
        Schema::table('mstmas', function (Blueprint $table) {
            $table->id();
            $table->string('braco');
            $table->string('cusno');
            $table->string('shpto');
            $table->string('shpnm');
            $table->text('deliveryaddress');
            $table->string('phone');
            $table->string('fax');
            $table->string('contp');
            $table->string('province');
            $table->string('kabupaten');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mstmas');
    }
};

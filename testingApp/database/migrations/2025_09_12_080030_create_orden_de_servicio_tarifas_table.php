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
        Schema::create('orden_de_servicio_tarifas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('orden_de_servicio_id');
            $table->unsignedBigInteger('tarifa_id');
            $table->timestamps();
            $table->foreign('orden_de_servicio_id')->references('id')->on('orden_de_servicios');
            $table->foreign('tarifa_id')->references('id')->on('tarifas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_de_servicio_tarifas');
    }
};

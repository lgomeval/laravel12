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
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('tipo');
            $table->string('nombre')->charset('utf8');
            $table->integer('precio');
            $table->integer('descuento');
            $table->string('estado')->default('activo');

            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('ciudad_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('ciudad_id')->references('id')->on('ciudads')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifas');
    }
};

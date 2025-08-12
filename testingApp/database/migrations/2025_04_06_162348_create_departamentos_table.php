<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

        DB::table('departamentos')->insert([
            ['nombre' => 'Amazonas'],
            ['nombre' => 'Antioquia'],
            ['nombre' => 'Arauca'],
            ['nombre' => 'Atlántico'],
            ['nombre' => 'Bolívar'],
            ['nombre' => 'Boyacá'],
            ['nombre' => 'Caldas'],
            ['nombre' => 'Caquetá'],
            ['nombre' => 'Casanare'],
            ['nombre' => 'Cauca'],
            ['nombre' => 'Cesar'],
            ['nombre' => 'Chocó'],
            ['nombre' => 'Córdoba'],
            ['nombre' => 'Cundinamarca'],
            ['nombre' => 'Guainía'],
            ['nombre' => 'Guaviare'],
            ['nombre' => 'Huila'],
            ['nombre' => 'La Guajira'],
            ['nombre' => 'Magdalena'],
            ['nombre' => 'Meta'],
            ['nombre' => 'Nariño'],
            ['nombre' => 'Norte de Santander'],
            ['nombre' => 'Putumayo'],
            ['nombre' => 'Quindío'],
            ['nombre' => 'Risaralda'],
            ['nombre' => 'San Andrés y Providencia'],
            ['nombre' => 'Santander'],
            ['nombre' => 'Sucre'],
            ['nombre' => 'Tolima'],
            ['nombre' => 'Valle del Cauca'],
            ['nombre' => 'Vaupés'],
            ['nombre' => 'Vichada'],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};

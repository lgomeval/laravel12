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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_examen');
            $table->string('tipo_examen');
            $table->date('fecha_cita');
            $table->time('hora_cita');
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['Agendada', 'Asistio', 'No Asistio', 'Cancelada', 'Pendiente Agendar'])->default('Pendiente Agendar');

            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('orden_de_servicio_id')->constrained('orden_de_servicios')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};

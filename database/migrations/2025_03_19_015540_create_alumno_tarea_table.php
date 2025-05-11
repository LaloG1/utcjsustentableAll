<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumno_tarea', function (Blueprint $table) {
            $table->id();
            $table->string('matricula'); // Matricula del alumno
            $table->foreign('matricula')->references('matricula')->on('alumnos')->onDelete('cascade');
            $table->unsignedBigInteger('tarea_id'); // ID de la tarea asignada
            $table->foreign('tarea_id')->references('id')->on('tareas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('alumno_tarea');
    }
};

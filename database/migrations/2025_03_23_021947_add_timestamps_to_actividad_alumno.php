<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('actividad_alumno', function (Blueprint $table) {
            $table->timestamps(); // Agrega created_at y updated_at
        });
    }

    public function down()
    {
        Schema::table('actividad_alumno', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};

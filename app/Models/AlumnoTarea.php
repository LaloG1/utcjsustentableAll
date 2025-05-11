<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnoTarea extends Model
{
    use HasFactory;

    protected $table = 'alumno_tarea';
    protected $fillable = ['alumno_id', 'tarea_id', 'imagen_1', 'imagen_2'];
}

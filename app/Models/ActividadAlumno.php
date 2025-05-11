<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadAlumno extends Model
{
    use HasFactory;

    protected $table = 'actividad_alumno';
    protected $fillable = ['actividad_id', 'alumno_id'];
}

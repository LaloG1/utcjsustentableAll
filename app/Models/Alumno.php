<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Authenticatable
{
    use HasFactory;

    protected $table = 'alumnos';
    protected $fillable = ['matricula', 'nombre', 'password'];

    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'actividad_alumno', 'alumno_id', 'actividad_id');
    }

    public function tareas()
    {
        return $this->belongsToMany(Tarea::class, 'alumno_tarea', 'alumno_id', 'tarea_id')
            ->withPivot('imagen_1', 'imagen_2');
    }
    
}

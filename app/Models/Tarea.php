<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';
    protected $fillable = ['nombre', 'descripcion'];

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_tarea', 'tarea_id', 'alumno_id')
            ->withPivot('imagen_1', 'imagen_2');
    }
}

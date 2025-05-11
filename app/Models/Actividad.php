<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividad';
    protected $fillable = ['Periodo', 'Horario'];

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'actividad_alumno', 'actividad_id', 'alumno_id');
    }
}

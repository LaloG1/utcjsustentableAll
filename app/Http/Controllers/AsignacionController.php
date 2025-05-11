<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Tarea;
use App\Models\AlumnoTarea;

class AsignacionController extends Controller {
    
    public function asignarTareas(Request $request) {
        // Obtener la lista de alumnos y tareas
        $alumnos = Alumno::all();
        $tareas = Tarea::all();

        // Verificar que haya alumnos y tareas disponibles
        if ($alumnos->isEmpty() || $tareas->isEmpty()) {
            return redirect()->back()->with('error', 'No hay alumnos o tareas disponibles.');
        }

        // Mezclar aleatoriamente la lista de tareas
        $tareasArray = $tareas->pluck('id')->shuffle()->toArray();

        // Asignar tareas de forma aleatoria a los alumnos
        foreach ($alumnos as $index => $alumno) {
            $tareaAsignada = $tareasArray[$index % count($tareasArray)]; // Asignación cíclica
            AlumnoTarea::create([
                'matricula' => $alumno->matricula,
                'tarea_id' => $tareaAsignada,
            ]);
        }

        return redirect()->back()->with('success', 'Las tareas fueron asignadas aleatoriamente a los alumnos.');
    }
}


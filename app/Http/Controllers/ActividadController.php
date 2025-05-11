<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividad;
use App\Models\Tarea;

class ActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {/* 
        $actividades = Actividad::select('id', 'Periodo', 'Horario')->get();
        return view('lista-actividades', compact('actividades')); */

        /* Se cargan actividades y tareas en el modal tambien */
        $actividades = Actividad::with('alumnos')->get();
        $tareas = Tarea::all(); // Carga todas las tareas

        return view('lista-actividades', compact('actividades', 'tareas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function registrarActividad(Request $request)
    {
        $alumnos = $request->input('alumnos', []);

        if (empty($alumnos)) {
            return back()->with('error', 'No hay alumnos cargados para crear una actividad.');
        }

        // Obtener la primera fila de alumnos
        $primerAlumno = reset($alumnos);

        // Verificar si existen las claves "Periodo" y "Horario"
        if (!isset($primerAlumno['Periodo']) || !isset($primerAlumno['Horario'])) {
            return back()->with('error', 'Faltan datos de Periodo u Horario en la lista de alumnos.');
        }

        // Crear la actividad en la base de datos
        $actividad = Actividad::create([
            'Periodo' => $primerAlumno['Periodo'],
            'Horario' => $primerAlumno['Horario']
        ]);

        return back()->with('success', 'Actividad creada exitosamente.');
    }
}

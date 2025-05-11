<?php

// app/Http/Controllers/TareaController.php
namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Support\Facades\DB;

class TareaListarController extends Controller
{
    // Mostrar todas las tareas
    public function index()
    {
        $tareas = Tarea::all(); // Obtener todas las tareas
        return view('crear-actividades', compact('tareas')); // Pasar las tareas a la vista
    }

}


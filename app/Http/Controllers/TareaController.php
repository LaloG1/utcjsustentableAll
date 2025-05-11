<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TareaController extends Controller
{
    // Mostrar la vista con las tareas
    public function index()
    {
        $tareas = Tarea::all();
        return view('lista-tareas', compact('tareas'));
    }

    // Guardar una nueva tarea
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'integrantes' => 'nullable|string',
        ]);

        Tarea::create($request->only(['nombre', 'descripcion', 'integrantes']));

        return redirect()->route('tareas.index')->with('success', 'Tarea creada exitosamente.');
    }

    // Actualizar una tarea existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'integrantes' => 'nullable|string',
        ]);

        $tarea = Tarea::findOrFail($id);
        $tarea->update($request->only(['nombre', 'descripcion', 'integrantes']));

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada correctamente.');
    }

    // Eliminar una tarea
    public function destroy($id)
    {
        $tarea = Tarea::findOrFail($id);
        $tarea->delete();

        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada correctamente.');
    }

    // Ver tareas asignadas a un alumno
    public function verTareas($id)
    {
        $alumno = Alumno::with('tareas')->findOrFail($id);
        return view('tareas-alumno', compact('alumno'));
    }

    // Guardar foto de la tarea
    public function guardarFoto(Request $request, $id)
    {
        try {
            $tarea = Tarea::findOrFail($id);

            if (!$request->hasFile('foto')) {
                return response()->json(['error' => 'No se recibió ninguna imagen'], 400);
            }

            $archivo = $request->file('foto');

            if (!$archivo->isValid()) {
                return response()->json(['error' => 'Archivo no válido'], 400);
            }

            $nombreArchivo = 'tarea_'.$id.'_'.time().'.'.$archivo->getClientOriginalExtension();
            $ruta = $archivo->storeAs('public/tareas', $nombreArchivo);

            if (!$ruta) {
                return response()->json(['error' => 'No se pudo guardar la imagen'], 500);
            }

            $rutaPublica = 'storage/tareas/' . $nombreArchivo;
            $tarea->update(['foto' => $rutaPublica]);

            \Log::info('📌 Ruta guardada en BD:', ['id' => $id, 'ruta' => $rutaPublica]);

            return response()->json([
                'mensaje' => '✅ Foto guardada con éxito',
                'ruta' => asset($rutaPublica),
                'db' => $rutaPublica
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => '⚠️ Error al guardar la foto',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    // Obtener tareas asignadas a un alumno por matrícula
    public function getTareasPorAlumno($matricula)
    {
        try {
            $alumno = Alumno::where('matricula', $matricula)->first();

            if (!$alumno) {
                return response()->json(['error' => 'Alumno no encontrado'], 404);
            }

            $tareas = DB::table('alumno_tarea')
            ->join('tareas', 'alumno_tarea.tarea_id', '=', 'tareas.id')
            ->select(
                'tareas.id',
                'tareas.nombre',
                'tareas.descripcion',
                'alumno_tarea.estado',
                'alumno_tarea.imagen_1',
                'alumno_tarea.imagen_2'
            )
            ->where('alumno_tarea.alumno_id', $alumno->id)
            ->get();


            return response()->json($tareas, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener tareas: ' . $e->getMessage()], 500);
        }
    }
}

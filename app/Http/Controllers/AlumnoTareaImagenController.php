<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AlumnoTarea;

class AlumnoTareaImagenController extends Controller
{
    public function guardarFoto(Request $request, $alumno_id, $tarea_id)
    {
        $registro = AlumnoTarea::where('alumno_id', $alumno_id)
                                ->where('tarea_id', $tarea_id)
                                ->first();

        if (!$registro) {
            return response()->json(['error' => 'No se encontró la tarea asignada al alumno.'], 404);
        }

        if (!$request->hasFile('foto')) {
            return response()->json(['error' => 'No se recibió ninguna imagen'], 400);
        }

        $archivo = $request->file('foto');

        if (!$archivo->isValid()) {
            return response()->json(['error' => 'Archivo no válido'], 400);
        }

        $timestamp = now()->format('Ymd_His');
        $nombreArchivo = "a{$alumno_id}_t{$tarea_id}_{$timestamp}." . $archivo->getClientOriginalExtension();

        // ✅ Guardar en storage/app/public/tareas
        $ruta = $archivo->storeAs('public/tareas', $nombreArchivo);

        // ✅ Ruta accesible vía navegador
        $rutaPublica = 'storage/tareas/' . $nombreArchivo;

        if (!$registro->imagen_1) {
            $registro->imagen_1 = $rutaPublica;
        } elseif (!$registro->imagen_2) {
            $registro->imagen_2 = $rutaPublica;
        } else {
            return response()->json(['error' => 'Ya se subieron 2 imágenes para esta tarea'], 400);
        }

        if ($registro->imagen_1 && $registro->imagen_2) {
            $registro->estado = 'Realizado';
        }

        $registro->save();

        return response()->json([
            'mensaje' => 'Imagen guardada correctamente',
            'nombre_archivo' => $nombreArchivo,
            'ruta_publica' => $rutaPublica
        ]);
    }

    public function subirFotoFinalizacion(Request $request, $alumno_id, $tarea_id)
    {
        $registro = AlumnoTarea::where('alumno_id', $alumno_id)
                                ->where('tarea_id', $tarea_id)
                                ->first();

        if (!$registro) {
            return response()->json(['error' => 'No se encontró la tarea asignada al alumno.'], 404);
        }

        if (!$request->hasFile('foto')) {
            return response()->json(['error' => 'No se recibió ninguna imagen'], 400);
        }

        $archivo = $request->file('foto');

        if (!$archivo->isValid()) {
            return response()->json(['error' => 'Archivo no válido'], 400);
        }

        $fecha = now()->format('Ymd_His');
        $nombreArchivo = "final_a{$alumno_id}_t{$tarea_id}_{$fecha}." . $archivo->getClientOriginalExtension();

        // ✅ Guardar en storage/app/public/tareas
        $ruta = $archivo->storeAs('public/tareas', $nombreArchivo);

        // ✅ Ruta accesible vía navegador
        $rutaPublica = 'storage/tareas/' . $nombreArchivo;

        if ($registro->imagen_2) {
            return response()->json(['error' => 'Ya se subió la imagen de finalización'], 400);
        }

        $registro->imagen_2 = $rutaPublica;

        if ($registro->imagen_1 && $registro->imagen_2) {
            $registro->estado = 'Realizado';
        }

        $registro->save();

        return response()->json([
            'mensaje' => 'Imagen final subida correctamente',
            'ruta' => $rutaPublica
        ]);
    }
}

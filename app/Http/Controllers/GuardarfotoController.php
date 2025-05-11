<?php

use Illuminate\Http\Request;
use App\Models\Tarea;
use Illuminate\Support\Facades\Storage;

class GuardarfotoController
{
    public function guardarFoto(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        if ($request->hasFile('foto')) {
            // Generar un nombre único con la fecha y el ID
            $nombreArchivo = 'tarea_'.$id.'_'.time().'.'.$request->foto->extension();

            // Guardar la imagen en `storage/app/public/tareas`
            $ruta = $request->foto->storeAs('public/tareas', $nombreArchivo);

            // Guardar la ruta en la base de datos
            $tarea->foto = 'storage/tareas/'.$nombreArchivo;
            $tarea->save();

            return response()->json(['mensaje' => 'Foto guardada con éxito', 'ruta' => $tarea->foto]);
        }

        return response()->json(['error' => 'No se recibió ninguna imagen'], 400);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\ActividadAlumno;
use App\Models\AlumnoTarea;
use Illuminate\Support\Facades\Hash;
use App\Models\Tarea;
use Resend\Laravel\Facades\Resend;

class Alumno2Controller extends Controller
{

public function registrarAlumno(Request $request)
{
    $request->validate([
        'actividad_id' => 'required|exists:actividad,id',
        'tarea_id' => 'required|exists:tareas,id',
        'matricula' => 'required|regex:/^al[0-9]{8}@utcj\.edu\.mx$/|unique:alumnos,matricula',
        'nombre' => 'required|string|max:255'
    ]);

    // Generar contraseña aleatoria
    $password = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
    $hashedPassword = Hash::make($password);

    // Crear o buscar al alumno
    $alumno = Alumno::firstOrCreate(
        ['matricula' => $request->matricula],
        ['nombre' => $request->nombre, 'password' => $hashedPassword]
    );

    // Asociar alumno con la actividad
    ActividadAlumno::firstOrCreate([
        'actividad_id' => $request->actividad_id,
        'alumno_id' => $alumno->id
    ]);

    // Asignar la tarea seleccionada al alumno
    AlumnoTarea::create([
        'alumno_id' => $alumno->id,
        'tarea_id' => $request->tarea_id
    ]);

    // Enviar correo al alumno con la contraseña
    try {
        Resend::emails()->send([
            'from' => 'UTCJ Sustentable <notificaciones@utcjsustentable.site>',
            'to' => $alumno->matricula,
            'subject' => 'Tus credenciales de acceso',
            'html' => view('emails.credenciales', [
                'matricula' => $alumno->matricula,
                'password' => $password,
                'nombre' => $alumno->nombre
            ])->render(),
        ]);

        return redirect()->back()->with('success', "Alumno registrado exitosamente. Se ha enviado la contraseña al correo del alumno.");
    } catch (\Exception $e) {
        // En caso de error al enviar el correo, puedes manejarlo aquí
        return redirect()->back()->with('success', "Alumno registrado exitosamente. Contraseña: $password")->with('warning', 'Pero hubo un problema al enviar el correo con las credenciales.');
    }
}
}

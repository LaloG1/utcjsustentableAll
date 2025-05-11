<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlumnoController extends Controller
{

    public function index()
    {
        $alumnos = Alumno::all(); // Obtener todos los alumnos
        return view('lista-alumnos', compact('alumnos'));
    }

    /**
     * Registra múltiples alumnos en la base de datos y genera contraseñas aleatorias.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registrar(Request $request)
    {
        $request->validate([
            'alumnos' => 'required|array',
            'alumnos.*.matricula' => 'required|string',
            'alumnos.*.nombre' => 'required|string',
        ]);

        $alumnosCreados = 0;
        $alumnosExistentes = 0;
        $alumnos = $request->alumnos;

        foreach ($alumnos as $alumnoData) {
            // Verificar si el alumno ya existe
            $alumnoExistente = Alumno::where('matricula', $alumnoData['matricula'])->first();

            if ($alumnoExistente) {
                $alumnosExistentes++;
                continue;
            }

            // Generar una contraseña alfanumérica aleatoria de 8 caracteres
            $password = $this->generarPassword();

            // Crear el nuevo alumno
            $alumno = new Alumno();
            $alumno->matricula = $alumnoData['matricula'];
            $alumno->nombre = $alumnoData['nombre'];
            $alumno->password = bcrypt($password); // Encriptar la contraseña
            $alumno->save();

            $alumnosCreados++;
        }

        // Redirigir con mensaje de éxito pero sin los datos registrados
        return redirect()->back()
            ->with('success', "Se han creado $alumnosCreados cuentas de alumnos correctamente. $alumnosExistentes ya existían.");
    }

    /**
     * Genera una contraseña alfanumérica aleatoria.
     *
     * @param  int  $length
     * @return string
     */
    private function generarPassword($length = 8)
    {
        // Caracteres permitidos para la contraseña (alfanuméricos)
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $passwordLength = strlen($caracteres);
        $password = '';

        // Generar la contraseña aleatoria
        for ($i = 0; $i < $length; $i++) {
            $password .= $caracteres[rand(0, $passwordLength - 1)];
        }

        return $password;
    }



}

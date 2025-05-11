<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\AlumnoTareaImagenController;


Route::post('/subir-foto-final/{alumno_id}/{tarea_id}', [AlumnoTareaImagenController::class, 'subirFotoFinalizacion']);
Route::post('/subir-foto-final/{alumno_id}/{tarea_id}', [AlumnoTareaImagenController::class, 'subirFotoFinalizacion']);
Route::post('/subir-foto/{alumno_id}/{tarea_id}', [AlumnoTareaImagenController::class, 'guardarFoto']);

// 📌 Limitar el número de peticiones por minuto
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->ip());
});

// 📌 Obtener tareas de un alumno por matrícula (usando el controlador)
Route::get('/tareas/alumno/{matricula}', [TareaController::class, 'getTareasPorAlumno']);

// 📌 Obtener actividades de un alumno por matrícula (directamente en la ruta)
Route::get('/actividades/{matricula}', function ($matricula) {
    $alumno = DB::table('alumnos')->where('matricula', $matricula)->first();

    if (!$alumno) {
        return response()->json(['message' => 'Alumno no encontrado'], 404);
    }

    $actividades = DB::table('actividad')
        ->join('actividad_alumno', 'actividad.id', '=', 'actividad_alumno.actividad_id')
        ->where('actividad_alumno.alumno_id', $alumno->id)
        ->select('actividad.*')
        ->get();

    return response()->json(['actividades' => $actividades]);
});

// 📌 Ruta de prueba para obtener todos los alumnos
Route::get('/prueba-alumno', function () {
    return App\Models\Alumno::all();
});

// 📌 Guardar foto en una tarea
Route::post('/guardar-foto/{id}', [TareaController::class, 'guardarFoto']);

// 📌 Obtener todos los datos de la base de datos
Route::get('/datos', [ApiController::class, 'index']);

// 📌 Validar login con la tabla alumnos
Route::post('/validar-alumno', function (Request $request) {
    $alumno = DB::table('alumnos')->where('matricula', $request->input('matricula'))->first();

    if (!$alumno || !Hash::check($request->input('password'), $alumno->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    return response()->json([
        'message' => 'Login exitoso',
        'alumno' => [
            'id' => $alumno->id,
            'matricula' => $alumno->matricula,
            'nombre' => $alumno->nombre,
        ]
    ]);
});
?>

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\TareaListarController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\ControladorCombinadoController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\Alumno2Controller;
use Illuminate\Support\Facades\Response;



Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard'); // Asegúrate de tener esta vista en resources/views
})->middleware(['auth', 'verified'])->name('dashboard');

/// Tareas
/* Despues de name, no pueden llamarse igual las rutas mas de una vez */
Route::get('/lista-tareas', [TareaController::class, 'index'])->name('tareas.index');
Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
Route::put('/tareas/{id}', [TareaController::class, 'update'])->name('tareas.update');
Route::delete('/tareas/{id}', [TareaController::class, 'destroy'])->name('tareas.destroy');

/// Lista de tareas
Route::get('/crear-actividad', [TareaController::class, 'show']);

/// Home
Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

/// Middleware de autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/// Rutas views
Route::get('/lista-actividades', function () {
    return view('lista-actividades');
});

Route::get('/tareas-alumnos', function () {
    return view('tareas-alumnos');
});

Route::get('/lista-alumnos', function () {
    return view('lista-alumnos');
});

Route::get('/crear-actividades', function () {
    return view('crear-actividades');
})->name('crear-actividades');

/// Ruta Crear Tarea
Route::get('/crear-actividades', [TareaListarController::class, 'index'])->name('actividades.create');

/// Recurso Tareas
Route::resource('tareas', TareaController::class);

/// Ruta para registrar alumnos
Route::post('/alumnos/registrar', [AlumnoController::class, 'registrar'])->name('alumnos.registrar');

/// Ruta para listar alumnos
Route::get('/lista-alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');

/// Ruta para registrar actividades
Route::post('/actividad/crear', [ActividadController::class, 'registrarActividad'])->name('actividad.crear');

/// Ruta para ejecutar todas las funciones de agregar alumno y crear actividad
Route::post('/ejecutar-todas-funciones', [ControladorCombinadoController::class, 'ejecutarTodasLasFunciones'])->name('ejecutar.todas.funciones');

/// Ruta para listar actividades
Route::get('/lista-actividades', [ActividadController::class, 'index']);

/// Ruta para asignar tarea a alumno
Route::post('/asignar-tareas', [AsignacionController::class, 'asignarTareas'])->name('asignar.tareas');

/// Ruta para ejecutar todas las actividades
Route::post('/ejecutar-todo', [ControladorCombinadoController::class, 'ejecutarTodo'])->name('ejecutar.todo');

/// Ruta para ver tareas de un alumno
Route::get('/tareas-alumno/{id}', [TareaController::class, 'verTareas']);

/// Ruta para registrar alumno
Route::post('/registrar-alumno', [Alumno2Controller::class, 'registrarAlumno'])->name('registrar.alumno');


Route::get('/ver-imagen/{filename}', function ($filename) {
    $path = storage_path('app/private/public/tareas/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);
});

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function index()
    {
        try {
            $database = env('DB_DATABASE'); // Obtiene el nombre de la base de datos
            $tables = DB::select('SHOW TABLES'); // Obtiene todas las tablas

            $data = [];

            foreach ($tables as $table) {
                $tableName = $table->{'Tables_in_' . $database}; // Nombre de la tabla
                $data[$tableName] = DB::table($tableName)->get(); // Obtiene todos los registros de la tabla
            }

            // Respuesta con encabezados CORS explícitos
            return response()->json($data, 200)
                ->header('Content-Type', 'application/json')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los datos: ' . $e->getMessage()], 500);
        }
    }
}

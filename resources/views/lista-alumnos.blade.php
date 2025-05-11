@extends('layouts.app2')

@section('content')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Lista de alumnos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Lista de alumnos</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-2">
                            <!-- <a href="{{ route('actividades.create') }}" class="btn btn-primary btn-sm px-4">Nuevo</a>
 -->
                        </div>
                        <style>
                            .clickable-row {
                                cursor: pointer;
                                transition: all 0.2s;
                            }

                            .clickable-row:hover {
                                background-color: #f8f9fa;
                                box-shadow: inset 0 0 0 9999px rgba(0, 0, 0, 0.01);
                            }

                            .clickable-row:active {
                                transform: scale(0.995);
                            }

                            /* Si usas tablas con zebra-striping */
                            .table-striped .clickable-row:nth-of-type(odd):hover {
                                background-color: #f1f1f1;
                            }
                        </style>
                        <!-- Tabla -->
                        <table id="actividadTable" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Matrícula</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Creado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alumnos as $alumno)
                                <tr class="clickable-row" data-id="{{ $alumno->id }}">
                                    <td><strong>{{ $alumno->id }}</strong></td> <!-- Solo el ID en negritas -->
                                    <td>{{ $alumno->matricula }}</td>
                                    <td>{{ $alumno->nombre }}</td>
                                    <td>{{ $alumno->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Fin Tabla -->
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

@endsection


<!-- jQuery DEBE cargarse primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Luego DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Inicializa DataTables normalmente
        var table = $('.Table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        });

        // 2. Sobrescribe el evento de búsqueda
        $('.dataTables_filter input').unbind().keyup(function() {
            // Limpia todas las búsquedas anteriores
            table.columns().search('');
            // Aplica la búsqueda solo a la columna de Nombre (índice 2)
            table.column(2).search(this.value).draw();
        });

        // 3. Mantén tu evento para filas clickeables
        $(document).on('click', '.clickable-row', function() {
            let alumnoId = $(this).data('id');
            window.location.href = "/tareas-alumno/" + alumnoId;
        });
    });
</script>

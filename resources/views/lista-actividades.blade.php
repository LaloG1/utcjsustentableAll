@extends('layouts.app2')
@section('content')

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Lista de actividades</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
        <li class="breadcrumb-item">Lista de actividades</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h5 class="card-title">Actividades creadas</h5>
            </div>
            <div class="d-flex align-items-center mb-2">
              <!-- <a href="" class="btn btn-primary btn-sm px-4 me-2">+ Alumno</a> -->

              <!-- Botón para abrir el modal -->
              <a href="#" class="btn btn-primary btn-sm px-4 me-2" data-bs-toggle="modal" data-bs-target="#addAlumnoModal">+ Alumno</a>

              <!-- Modal -->
              <!-- Modal para agregar alumno -->
              <div class="modal fade" id="addAlumnoModal" tabindex="-1" aria-labelledby="addAlumnoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="addAlumnoModalLabel">Agregar Alumno</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                      <!-- Mensaje de éxito -->
                      @if(session('success'))
                      <div class="alert alert-success">
                        {{ session('success') }}
                      </div>
                      @endif

                      <!-- Mensaje de error -->
                      @if ($errors->any())
                      <div class="alert alert-danger">
                        <ul>
                          @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                      @endif

                      <form action="{{ route('registrar.alumno') }}" method="POST">
                        @csrf

                        <!-- Select para Actividades -->
                        <div class="mb-3">
                          <label for="actividadSelect" class="form-label">Seleccionar Actividad</label>
                          <select id="actividadSelect" name="actividad_id" class="form-control" required>
                            <option value="">Seleccione una actividad</option>
                            @foreach($actividades as $actividad)
                            <option value="{{ $actividad->id }}">{{ $actividad->Periodo }} - {{ $actividad->Horario }}</option>
                            @endforeach
                          </select>
                        </div>

                        <!-- Select para Tareas -->
                        <div class="mb-3">
                          <label for="tareaSelect" class="form-label">Seleccionar Tarea</label>
                          <select id="tareaSelect" name="tarea_id" class="form-control" required>
                            <option value="">Seleccione una tarea</option>
                            @foreach($tareas as $tarea)
                            <option value="{{ $tarea->id }}">{{ $tarea->nombre }}</option>
                            @endforeach
                          </select>
                        </div>

                        <!-- Campo para Matrícula -->
                        <div class="mb-3">
                          <label for="matriculaInput" class="form-label">Matrícula</label>
                          <input type="text" id="matriculaInput" name="matricula" class="form-control"
                            pattern="al[0-9]{8}@utcj\.edu\.mx" placeholder="Ej. al12345678@utcj.edu.mx" required>
                          <div class="invalid-feedback">Formato incorrecto: al+8 números+@utcj.edu.mx</div>
                        </div>

                        <!-- Campo para Nombre -->
                        <div class="mb-3">
                          <label for="nombreInput" class="form-label">Nombre</label>
                          <input type="text" id="nombreInput" name="nombre" class="form-control"
                            oninput="this.value = this.value.toUpperCase()" placeholder="Nombre del alumno" required>
                        </div>

                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                          <button type="submit" class="btn btn-success">Guardar Alumno</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex-grow-1"></div>
              <a href="{{ route('actividades.create') }}" class="btn btn-primary btn-sm px-4">Nueva actividad</a>
            </div>

            <!-- Table with stripped rows -->
            <!-- Table with stripped rows -->
            <table id="actividadTable" class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Id</th>
                  <th scope="col">Periodo</th>
                  <th scope="col">Horario</th>
                  <th scope="col"># Alumnos</th>
                  <th scope="col">Estado</th> <!-- Nueva columna Estado -->
                </tr>
              </thead>
              <tbody>
                @foreach($actividades as $index => $actividad)
                <tr data-id="{{ $actividad->id }}" class="parent-row">
                  <td>{{ $index + 1 }}</td>
                  <td><a href="javascript:void(0)"><strong>{{ $actividad->id }}</strong></a></td>
                  <td>{{ $actividad->Periodo }}</td>
                  <td>{{ $actividad->Horario }}</td>
                  <td>{{ $actividad->alumnos->count() }}</td>
                  <td>
                    @php
                    $todosCompletados = true;
                    foreach($actividad->alumnos as $alumno) {
                    foreach($alumno->tareas as $tarea) {
                    if(empty($tarea->pivot->imagen_1) || empty($tarea->pivot->imagen_2)) {
                    $todosCompletados = false;
                    break 2; // Sale de ambos bucles
                    }
                    }
                    }
                    @endphp
                    @if($todosCompletados && $actividad->alumnos->count() > 0)
                    <span class="badge text-bg-success">Completado</span>
                    @else
                    <span class="badge text-bg-warning">Pendiente</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

            <!-- Hidden student tables (se mantiene igual) -->
            @foreach($actividades as $actividad)
            <div id="child-row-{{ $actividad->id }}" class="child-row mt-2 mb-4" style="display:none;">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>ID Alumno</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($actividad->alumnos as $index => $alumno)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                      <a href="javascript:void(0)" class="alumno-link" data-id="{{ $alumno->id }}">
                        <strong>{{ $alumno->id }}</strong>
                      </a>
                    </td>
                    <td>{{ $alumno->nombre }}</td>
                    <td>
                      @php
                      $completado = true;
                      foreach($alumno->tareas as $tarea) {
                      if(empty($tarea->pivot->imagen_1) || empty($tarea->pivot->imagen_2)) {
                      $completado = false;
                      break;
                      }
                      }
                      @endphp
                      @if($completado && count($alumno->tareas) > 0)
                      <span class="badge text-bg-success">Completado</span>
                      @else
                      <span class="badge text-bg-warning">Pendiente</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endforeach
            <!-- End Hidden student tables -->

          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<!-- End #main -->

@endsection

<!-- Scripts should be at the end -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function() {
    // Initialize DataTable first
    $('#actividadTable').DataTable();

    // Manejar el clic en las filas de la tabla
    $('#actividadTable tbody').on('click', 'tr.parent-row', function() {
      var actividadId = $(this).data('id');
      var childRow = $('#child-row-' + actividadId);

      // Alternar la visibilidad de la fila secundaria
      if (childRow.is(':hidden')) {
        $('.child-row').hide(); // Hide all other open child rows
        childRow.show();
      } else {
        childRow.hide();
      }
    });

    // Event listener for student links
    $(document).on('click', '.alumno-link', function() {
      let alumnoId = $(this).data('id');
      window.location.href = "/tareas-alumno/" + alumnoId;
    });
  });
</script>
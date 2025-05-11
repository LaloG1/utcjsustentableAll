@extends('layouts.app2')
@section('content')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Tareas de alumnos</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Tareas de alumnos</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h5 class="card-title">{{ $alumno->nombre }}</h5>
            </div>
            <!-- Tabla -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tarea</th>
                  <th>Foto 1</th>
                  <th>Foto 2</th>
                  <th>Estado</th> <!-- Nueva columna Estado -->
                </tr>
              </thead>
              <tbody>
                @foreach($alumno->tareas as $index => $tarea)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $tarea->nombre }}</td>

                  <td>
                    @if($tarea->pivot->imagen_1)
                    @php
                    $fileName = basename($tarea->pivot->imagen_1);
                    @endphp
                    <button class="btn btn-link p-0" onclick="mostrarImagen('{{ url('/ver-imagen/'.$fileName) }}')">Ver Foto 1</button>
                    @else
                    No disponible
                    @endif
                  </td>

                  <td>
                    @if($tarea->pivot->imagen_2)
                    @php
                    $fileName = basename($tarea->pivot->imagen_2);
                    @endphp
                    <button class="btn btn-link p-0" onclick="mostrarImagen('{{ url('/ver-imagen/'.$fileName) }}')">Ver Foto 2</button>
                    @else
                    No disponible
                    @endif
                  </td>

                  <!-- Nueva columna Estado -->
                  <td>
                    @if($tarea->pivot->imagen_1 && $tarea->pivot->imagen_2)
                    <span class="badge text-bg-success">Completado</span>
                    @else
                    <span class="badge text-bg-warning">Pendiente</span>
                    @endif
                  </td>
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

<!-- Modal -->
<div class="modal fade" id="modalImagen" tabindex="-1" aria-labelledby="imagenModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Vista de la imagen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <img id="imagenModal" src="" class="img-fluid rounded shadow" alt="Imagen de tarea">
      </div>
    </div>
  </div>
</div>

<script>
  function mostrarImagen(ruta) {
    document.getElementById('imagenModal').src = ruta;
    new bootstrap.Modal(document.getElementById('modalImagen')).show();
  }
</script>

@endsection
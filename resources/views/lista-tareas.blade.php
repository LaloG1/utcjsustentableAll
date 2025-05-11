@extends('layouts.app2')

@section('content')

<main id="main" class="main">

  {{-- Mensaje de éxito --}}
  @if(session('success'))
  <p class="alert alert-success">{{ session('success') }}</p>
  @endif

  <div class="pagetitle">
    <h1>Lista de Tareas</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
        <li class="breadcrumb-item active">Lista de Tareas</li>
      </ol>
    </nav>
  </div>

  <div class="container">
    <div class="card card-default">
      <div class="card-body">
        <h3>Crear Nueva Tarea</h3>
        <!-- Formulario para crear nueva tarea -->
        <!-- resources/views/tareas/index.blade.php -->
        <form action="{{ route('tareas.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <div class="col-sm-6">
              <input id="name" type="text" class="form-control" name="nombre" placeholder="Nombre de la tarea" required autofocus>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
              <textarea id="description" class="form-control" name="descripcion" placeholder="Descripción de la tarea"></textarea>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
              <input id="integrantes" type="text" class="form-control" name="integrantes" placeholder="Integrantes de la tarea" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary mb-2">Guardar</button>
        </form>

      </div>
    </div>

    <!-- resources/views/tareas/index.blade.php -->
    <table id="tareasTable" class="table datatable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Integrantes</th> <!-- Nueva columna -->
          <th>Fecha de Creación</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($tareas as $tarea)
        <tr>
          <td>{{ $tarea->id }}</td>
          <td>{{ $tarea->nombre }}</td>
          <td>{{ $tarea->descripcion }}</td>
          <td>{{ $tarea->integrantes }}</td> <!-- Mostrar el campo 'integrantes' -->
          <td>{{ $tarea->created_at->format('d/m/Y H:i') }}</td>
          <td class="text-center">
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $tarea->id }}">Editar</button>
            <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>


    <!-- resources/views/tareas/index.blade.php -->
    @foreach ($tareas as $tarea)
    <!-- Modal de edición -->
    <div class="modal fade" id="editModal{{ $tarea->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $tarea->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel{{ $tarea->id }}">Editar Tarea: {{ $tarea->nombre }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('tareas.update', $tarea->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label for="name">Nombre de la tarea</label>
                <input id="name" type="text" class="form-control" name="nombre" value="{{ $tarea->nombre }}" required autofocus>
              </div>
              <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" class="form-control" name="descripcion">{{ $tarea->descripcion }}</textarea>
              </div>
              <div class="form-group">
                <label for="integrantes">Integrantes</label>
                <input id="integrantes" type="text" class="form-control" name="integrantes" value="{{ $tarea->integrantes }}">
              </div>
              <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endforeach

  </div>
</main>

@endsection

<!-- Incluir los scripts de jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
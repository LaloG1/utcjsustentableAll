@extends('layouts.app2')
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
      <h1>Creacion de cuentas y actividades</h1>
      <form action="{{ route('ejecutar.todo') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Crear Usuarios-asig-activ</button>
      </form>
    </div>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
        <li class="breadcrumb-item">Crear actividades</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <!-- En la vista -->
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-weight: bold; font-size: 1.1em;">
    <i class="bi bi-check-circle me-1"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  @if(session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
  @endif

  <section class="section">
    <div class="row">
      <div class="col-lg-6">

        <div class="card">
          <div class="card-body">
            <!-- Input file -->
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Lista de Alumnos:</h5>
              <!-- <a href="#" class="btn btn-primary" id="xxxx">+ Alumno</a> -->
              <a href="#" class="btn btn-primary" id="btnCargarLista">Cargar lista</a>
            </div>

            <!-- Input file oculto para seleccionar archivos Excel -->
            <input class="form-control" type="file" id="formFile" style="display: none;" accept=".xls, .xlsx">

            <!-- Tabla para mostrar los datos cargados -->
            <table class="table table-hover" id="tablaAlumnos">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">PERIODO</th>
                  <th scope="col">HORARIO</th>
                  <th scope="col">MATRICULA</th>
                  <th scope="col">NOMBRE</th>
                </tr>
              </thead>
              <tbody id="tbodyAlumnos">
                <!-- Aquí se cargarán los datos del Excel con JavaScript -->
              </tbody>

              <!-- Aquí agregamos un formulario oculto que se llenará dinámicamente con JavaScript -->
              <form id="alumnosForm" action="{{ route('alumnos.registrar') }}" method="POST" style="display: none;">
                @csrf
                <!-- Los campos de alumnos se agregarán dinámicamente aquí -->
              </form>
            </table>
            <!-- End Table with hoverable rows -->
          </div>
        </div>
      </div>

      <!-- Tabla lista de tareas -->
      <div class="col-lg-5 d-flex justify-content-end">
        <div class="card" style="max-width: 600px;">
          <div class="card-body">
            <h5 class="card-title">Lista de tareas:</h5>
            <select class="form-select" id="taskSelect" aria-label="Default select example">
              <option selected disabled>Seleccione una tarea</option>
              @foreach ($tareas as $tarea)
              <option value="{{ $tarea->id }}"
                data-nombre="{{ $tarea->nombre }}"
                data-integrantes="{{ $tarea->integrantes }}">
                {{ $tarea->nombre }}
              </option>
              @endforeach
            </select>
            <!-- Tabla con filas desmarcadas -->
            <table class="table table-striped table-sm" id="tablaTareas">
              <thead>
                <tr>
                  <th scope="col" style="width: 10%;">#</th>
                  <th style="width: 60%;">Nombre</th>
                  <!-- <th style="width: 10%;">Integrantes</th> -->
                  <th scope="col" style="width: 20%;">Acción</th>
                </tr>
              </thead>
              <tbody id="tbodyTareas">
                <!-- Las filas se agregarán dinámicamente aquí -->
              </tbody>
            </table>
            <!-- Fin de la tabla -->
          </div>
        </div>
      </div>
  </section>
</main>
<!-- End #main -->
@endsection

<!-- Firebase Scripts -->
<script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Agregar la librería XLSX para procesar archivos Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>

<script>
  $(document).ready(function() {
    // Al hacer clic en el botón, abre el input file
    $("#btnCargarLista").click(function(event) {
      event.preventDefault();
      $("#formFile").click();
    });

    // Al seleccionar un archivo, procesarlo
    $("#formFile").change(function(event) {
      var file = event.target.files[0]; // Obtener el archivo seleccionado
      if (!file) return;

      var reader = new FileReader();
      reader.readAsArrayBuffer(file);

      reader.onload = function(e) {
        var data = new Uint8Array(e.target.result);
        var workbook = XLSX.read(data, {
          type: "array"
        });

        var firstSheet = workbook.SheetNames[0]; // Tomar la primera hoja del archivo
        var sheetData = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheet], {
          defval: ""
        });

        // Limpiar la tabla antes de cargar los datos
        $("#tbodyAlumnos").empty();

        // Limpiar también el formulario oculto
        $("#alumnosForm").html('');
        $("#alumnosForm").append('@csrf');

        // Iterar sobre los datos y cargar solo las columnas deseadas
        sheetData.forEach((row, index) => {
          if (row["PERIODO"] && row["HORARIO"] && row["MATRICULA"] && row["NOMBRE"]) {
            var formattedMatricula = `al${row["MATRICULA"]}@utcj.edu.mx`;

            var newRow = `
              <tr>
                <th scope="row">${index + 1}</th>
                <td>${row["PERIODO"]}</td>
                <td>${row["HORARIO"]}</td>
                <td>${formattedMatricula}</td>
                <td>${row["NOMBRE"]}</td>
              </tr>
            `;
            $("#tbodyAlumnos").append(newRow);

            // Agregar los campos ocultos al formulario para cada alumno
            $("#alumnosForm").append(`
              <input type="hidden" name="alumnos[${index}][matricula]" value="${formattedMatricula}">
              <input type="hidden" name="alumnos[${index}][nombre]" value="${row["NOMBRE"]}">
              <input type="hidden" name="alumnos[0][Periodo]" value="${row["PERIODO"]}">
              <input type="hidden" name="alumnos[0][Horario]" value="${row["HORARIO"]}">
            `);
          }
        });

        // Hacemos visible el botón de envío después de cargar los datos
        $('button[type="submit"]').prop('disabled', false);

        // Limpiar el input file para permitir cargar el mismo archivo nuevamente
        $("#formFile").val("");
      };
    });

    // Modificamos el formulario para que use los datos ocultos
    $("form").submit(function() {
      // Verificar si hay alumnos cargados
      if ($("#tbodyAlumnos tr").length === 0) {
        alert("No hay alumnos para registrar. Por favor cargue una lista primero.");
        return false;
      }

      if ($("#tbodyTareas tr").length === 0) {
        alert("No hay tareas para asignar. Por favor cargue tareas primero.");
        return false;
      }

      // Transferir los campos del formulario oculto al formulario principal
      $("#alumnosForm input").clone().appendTo($(this));

      return true;
    });
  });
</script>

<!-- Lista de tareas - AGREGAR TAREAS A LA TABLA -->
<script>
  $(document).ready(function() {
    // Cuando se hace clic en un botón de eliminar
    $(".btn-eliminar").click(function() {
      // Obtener el ID de la tarea
      var tareaId = $(this).data("id");

      // Eliminar la fila correspondiente de la tabla
      $("#tarea_" + tareaId).remove();
    });
  });
</script>

<script>
  $(document).ready(function() {
    let counter = 1; // Inicializamos un contador

    // Al seleccionar una tarea del select
    $("#taskSelect").change(function() {
      // Obtener el ID, el nombre de la tarea y los integrantes de la tarea seleccionada
      var tareaId = $(this).val();
      var tareaNombre = $(this).find(":selected").data("nombre");
      var tareaIntegrantes = $(this).find(":selected").data("integrantes");

      // Verificar si ya existe en la tabla
      if ($("#tarea_" + tareaId).length === 0) {
        // Si no existe, agregarla a la tabla
        var nuevaFila = `
        <tr id="tarea_${tareaId}">
          <th scope="row">${counter}</th>  <!-- Mostrar el contador como ID -->
          <td>${tareaNombre}</td>
          <!-- <td>${tareaIntegrantes}</td>  Mostrar integrantes -->
          <td>
            <button class="btn btn-danger btn-eliminar" data-id="${tareaId}">Eliminar</button>
          </td>
        </tr>
      `;
        // Agregar la nueva fila a la tabla
        $("#tablaTareas tbody").append(nuevaFila);

        // Aumentar el contador para la siguiente fila
        counter++;
      } else {
        alert("La tarea ya está en la lista.");
      }
    });

    // Cuando se haga clic en el botón de eliminar, eliminar la fila de la tabla
    $(document).on("click", ".btn-eliminar", function() {
      var tareaId = $(this).data("id");
      $("#tarea_" + tareaId).remove();

      // Después de eliminar una fila, actualizar el contador para el siguiente ID
      counter = 1; // Reiniciar el contador para no repetir números
      $("#tablaTareas tbody tr").each(function(index) {
        $(this).find("th").text(index + 1); // Reasignar los valores de ID
      });
    });
  });
</script>

<script>
  // Hacer que los mensajes de alerta se mantengan visibles por 5 segundos
  $(document).ready(function() {
    // Mostrar las alertas
    $('.alert').show();

    // Establecer un temporizador para ocultar las alertas después de 5 segundos
    setTimeout(function() {
      $('.alert').fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
      });
    }, 5000);
  });
</script>
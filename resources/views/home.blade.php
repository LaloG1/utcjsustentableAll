@extends('layouts.app2')

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Pantalla principal</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div>
  <!-- Left side columns -->
  <div style="display: flex; justify-content: center;">
    <div class="row">
      <img src="{{ asset('dist/img/utsustentable.jpg') }}" alt="Logo" class="img-fluid" style="width: 500px; height: auto;">
    </div>
  </div>
</main>
@endsection
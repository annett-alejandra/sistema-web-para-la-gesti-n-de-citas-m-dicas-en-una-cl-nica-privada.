@extends('layouts.app')

@section('title', 'Panel | Clínica Vida Salud')

@section('content')
<div class="container clinica-module">
    <x-clinica-heading caso="Paquete 01 · CU02" title="Panel de Clínica Vida Salud" />
    <section class="clinica-card">
        <h2>{{ auth()->user()->nombre_usuario }}</h2>
        <x-status-badge :label="auth()->user()->rol->nombre_rol" type="info" />
        <p>Seleccione un módulo en el menú superior.</p>
    </section>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Especialidades médicas | Clínica Vida Salud')

@section('content')
<div class="container clinica-module">
    <x-clinica-heading caso="Paquete 01 · CU04" title="Catálogo de especialidades médicas" />
    <div class="clinica-actions">
        <a class="btn-primary" href="{{ route('especialidades.index') }}#formulario">Nueva especialidad</a>
    </div>
    <div class="clinica-fields">
        @forelse ($especialidades as $especialidad)
            <article class="clinica-card">
                <h2>{{ $especialidad->nombre_especialidad }}</h2>
                <x-status-badge :type="$especialidad->estado ? 'stable' : 'caution'" :label="$especialidad->estado ? 'Activa' : 'Inactiva'" />
                <p>{{ $especialidad->descripcion }}</p>
                <x-clinica-row-actions module="especialidades" :record="$especialidad" />
            </article>
        @empty
            <p>No se encontraron especialidades.</p>
        @endforelse
    </div>
    <section class="clinica-card" id="formulario">
        <h2>{{ $edicion ? 'Modificar especialidad' : 'Registrar especialidad médica' }}</h2>
        <x-form :action="$edicion ? route('especialidades.update', $edicion) : route('especialidades.store')" :method="$edicion ? 'PUT' : 'POST'" class="clinica-form">
            <x-clinica-input name="nombre_especialidad" label="Nombre de la especialidad" :value="$edicion?->nombre_especialidad" maxlength="150" required />
            <x-clinica-input name="descripcion" label="Descripción y alcance clínico" type="textarea" :value="$edicion?->descripcion" rows="3" />
            <div class="clinica-actions">
                <button class="btn-primary" type="submit">Guardar especialidad</button>
                @if ($edicion)<a class="btn-outline" href="{{ route('especialidades.index') }}">Cancelar edición</a>@endif
            </div>
        </x-form>
    </section>
</div>
@endsection

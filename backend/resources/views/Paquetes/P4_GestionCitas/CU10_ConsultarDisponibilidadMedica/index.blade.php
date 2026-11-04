@extends('layouts.app')

@section('title', 'Disponibilidad médica | Clínica Vida Salud')

@section('content')
<div class="container clinica-module">
    <x-clinica-heading caso="Paquete 04 · CU10" title="Consulta de disponibilidad médica"
        description="Busque turnos libres por especialidad, médico y fecha." />
    <x-form :action="route('disponibilidad.index')" method="GET" class="clinica-card clinica-form">
        <div class="clinica-fields">
            <x-clinica-select name="id_especialidad" label="Especialidad" :options="$especialidades->pluck('nombre_especialidad', 'id_especialidad')"
                :value="$filtros['id_especialidad'] ?? null" :use-old="false" required />
            <x-clinica-select name="id_medico" label="Médico especialista (opcional)" :options="$medicos->pluck('nombre_completo', 'id_medico')"
                :value="$filtros['id_medico'] ?? null" :use-old="false" placeholder="Todos los médicos" />
            <x-clinica-input name="fecha" label="Fecha de consulta" type="date" :value="$filtros['fecha'] ?? now()->toDateString()" :min="now()->toDateString()" required />
        </div>
        <button class="btn-primary" type="submit">Consultar oferta</button>
    </x-form>
    @if ($erroresConsulta)
        <div class="notice notice-error" role="alert">
            <ul>@foreach ($erroresConsulta as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    @if ($disponibilidad !== null)
        <div class="clinica-fields">
            @forelse ($disponibilidad as $oferta)
                <article class="clinica-card">
                    <h2>{{ $oferta['medico']['nombre_completo'] }}</h2>
                    <p>{{ $oferta['especialidad']['nombre_especialidad'] }} · {{ $oferta['fecha'] }}</p>
                    <p>{{ $oferta['total_cupos'] }} turnos libres</p>
                    <ul class="clinica-slots">
                        @foreach ($oferta['cupos'] as $cupo)
                            <li>
                                @if (auth()->user()->can('reservar') && $cupo['duracion_minutos'] <= 180)
                                    <a class="btn-outline" href="{{ route('citas.index', [
                                        'id_medico' => $oferta['medico']['id_medico'],
                                        'id_especialidad' => $oferta['especialidad']['id_especialidad'],
                                        'fecha_cita' => $oferta['fecha'],
                                        'hora_cita' => $cupo['hora_inicio'],
                                        'duracion_minutos' => $cupo['duracion_minutos'],
                                    ]) }}">
                                        {{ $cupo['hora_inicio'] }} – {{ $cupo['hora_fin'] }} · Seleccionar
                                    </a>
                                @else
                                    <span>{{ $cupo['hora_inicio'] }} – {{ $cupo['hora_fin'] }}</span>
                                    @can('reservar')
                                        <small>Este turno supera el máximo de 180 min por cita.</small>
                                    @endcan
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </article>
            @empty
                <p class="clinica-card">No hay turnos disponibles con estos filtros.</p>
            @endforelse
        </div>
    @endif
</div>
@endsection

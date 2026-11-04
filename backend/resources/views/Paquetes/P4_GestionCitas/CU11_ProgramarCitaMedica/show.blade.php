@extends('layouts.app')

@section('title', 'Detalle de cita | Clínica Vida Salud')

@section('content')
<div class="container clinica-module clinica-narrow">
    <x-clinica-heading caso="Paquete 04 · CU11" title="Detalle de cita médica" />
    <article class="clinica-card">
        <x-status-badge :label="$cita->estadoCita?->nombre_estado ?? 'Estado sin identificar'" type="info" />
        <dl class="clinica-list">
            <div><dt>Código de reserva</dt><dd><code>{{ $cita->codigo_unico }}</code></dd></div>
            <div><dt>Paciente</dt><dd>{{ $cita->paciente?->nombre_completo }}</dd></div>
            <div><dt>Especialidad y médico</dt><dd>{{ $cita->especialidad?->nombre_especialidad }} · {{ $cita->medico?->nombre_completo }}</dd></div>
            <div><dt>Fecha y hora</dt><dd>{{ $cita->fecha_cita?->format('d/m/Y') }} a las {{ substr($cita->hora_cita, 0, 5) }} · {{ $cita->duracion_minutos }} min</dd></div>
            @if ($cita->motivo)
                <div><dt>Motivo de consulta</dt><dd>{{ $cita->motivo }}</dd></div>
            @endif
        </dl>
        <a class="btn-outline" href="{{ route('citas.index') }}">Volver a citas</a>
    </article>
</div>
@endsection

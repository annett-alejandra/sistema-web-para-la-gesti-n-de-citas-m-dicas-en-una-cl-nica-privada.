@extends('layouts.app')

@section('title', 'Citas médicas | Clínica Vida Salud')

@section('content')
<div class="container clinica-module">
    <x-clinica-heading caso="Paquete 04 · CU11" title="Programación y reserva de citas médicas"
        description="Consulte sus citas o programe un turno según su acceso." />
    <div class="clinica-booking">
        @can('reservar')
            <section class="clinica-card">
                <h2>Nueva reserva</h2>
                <p><a href="{{ route('disponibilidad.index') }}">Consultar turnos disponibles</a></p>
                <x-form :action="route('citas.store')" class="clinica-form">
                    @can('recepcion')
                        <x-clinica-select name="id_paciente" label="Paciente"
                            :options="$pacientes->mapWithKeys(fn ($p) => [$p->id_paciente => $p->nombre_completo.' (CI '.$p->ci.')'])" required />
                    @else
                        <p>La reserva se asignará a la ficha vinculada a su cuenta.</p>
                    @endcan
                    <x-clinica-select name="id_especialidad" label="Especialidad"
                        :options="$especialidades->pluck('nombre_especialidad', 'id_especialidad')" :value="request()->query('id_especialidad')" required />
                    <x-clinica-select name="id_medico" label="Médico"
                        :options="$medicos->mapWithKeys(fn ($m) => [$m->id_medico => $m->nombre_completo.' ('.$m->registro_profesional.')'])"
                        :value="request()->query('id_medico')" required />
                    <div class="clinica-fields">
                        <x-clinica-input name="fecha_cita" label="Fecha" type="date" :min="now()->toDateString()" :value="request()->query('fecha_cita')" required />
                        <x-clinica-input name="hora_cita" label="Hora" type="time" :value="request()->query('hora_cita')" required />
                    </div>
                    <x-clinica-input name="duracion_minutos" label="Duración (minutos)" type="number" :value="request()->query('duracion_minutos', 30)" min="5" max="180" required />
                    <x-clinica-input name="motivo" label="Motivo de consulta (opcional)" type="textarea" maxlength="500" rows="2" />
                    <button type="submit" class="btn-primary">Confirmar reserva</button>
                </x-form>
            </section>
        @endcan
        <section class="clinica-card">
            <h2>Citas registradas</h2>
            <x-form :action="route('citas.index')" method="GET" class="clinica-actions">
                <x-clinica-input name="fecha" label="Filtrar por fecha" type="date" :value="$filtros['fecha'] ?? null" />
                <button class="btn-outline" type="submit">Filtrar</button>
                @if ($filtros)
                    <a class="btn-outline" href="{{ route('citas.index') }}">Limpiar filtros</a>
                @endif
            </x-form>
            <div class="clinica-list">
                @forelse ($citas as $cita)
                    <article class="clinica-appointment">
                        <x-status-badge :label="$cita->estadoCita?->nombre_estado ?? 'Estado sin identificar'" type="info" />
                        <p>{{ $cita->fecha_cita?->format('d/m/Y') }} a las {{ substr($cita->hora_cita, 0, 5) }}</p>
                        <h3>{{ $cita->paciente?->nombre_completo }}</h3>
                        <p>{{ $cita->medico?->nombre_completo }} · {{ $cita->especialidad?->nombre_especialidad }}</p>
                        <a class="btn-outline" href="{{ route('citas.show', $cita->codigo_unico) }}">Ver detalle y código</a>
                    </article>
                @empty
                    <p>No se encontraron citas.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection

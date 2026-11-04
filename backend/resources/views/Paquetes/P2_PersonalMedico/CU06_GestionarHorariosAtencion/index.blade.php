@extends('layouts.app')

@section('title', 'Horarios de atención | Clínica Vida Salud')

@section('content')
<div class="container clinica-module">
    <x-clinica-heading caso="Paquete 02 · CU06" title="Horarios y turnos de atención" />
    @can('recepcion')
        <x-form :action="route('horarios.index')" method="GET" class="clinica-actions">
            <x-clinica-select name="id_medico" label="Filtrar por médico" :options="$medicos->pluck('nombre_completo', 'id_medico')"
                :value="$idMedico" placeholder="Todos los médicos" :use-old="false" />
            <button class="btn-outline" type="submit">Filtrar</button>
            <a class="btn-primary" href="#formulario">Nuevo turno</a>
        </x-form>
    @endcan
    <div class="clinica-table">
        <table>
            <thead><tr>
                <th scope="col">Médico</th><th scope="col">Día</th><th scope="col">Horario</th><th scope="col">Duración por cita</th>
                @can('recepcion')<th scope="col">Acciones</th>@endcan
            </tr></thead>
            <tbody>
                @forelse ($horarios as $horario)
                    <tr>
                        <td>{{ $horario->medico?->nombre_completo }}</td>
                        <td>{{ $dias[$horario->dia_semana] ?? '—' }}</td>
                        <td>{{ substr($horario->hora_inicio, 0, 5) }} – {{ substr($horario->hora_fin, 0, 5) }}</td>
                        <td>{{ $horario->duracion_cita_minutos }} min</td>
                        @can('recepcion')
                            <td><x-form :action="route('horarios.destroy', $horario)" method="DELETE">
                                <button class="btn-outline" type="submit">Eliminar turno</button>
                            </x-form></td>
                        @endcan
                    </tr>
                @empty
                    <tr><td colspan="{{ auth()->user()->can('recepcion') ? 5 : 4 }}">No se encontraron horarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @can('recepcion')
        <section class="clinica-card" id="formulario">
            <h2>Configurar turno de atención</h2>
            <x-form :action="route('horarios.store')" class="clinica-form">
                <div class="clinica-fields">
                    <x-clinica-select name="id_medico" label="Médico" :options="$medicos->pluck('nombre_completo', 'id_medico')" :value="$idMedico" required />
                    <x-clinica-select name="dia_semana" label="Día de atención" :options="$dias" required />
                    <x-clinica-input name="hora_inicio" label="Hora de inicio" type="time" required />
                    <x-clinica-input name="hora_fin" label="Hora de fin" type="time" required />
                    <x-clinica-input name="duracion_cita_minutos" label="Duración de cada cita (minutos)" type="number" value="30" min="5" max="240" required />
                </div>
                <button class="btn-primary" type="submit">Guardar turno</button>
            </x-form>
        </section>
    @endcan
</div>
@endsection

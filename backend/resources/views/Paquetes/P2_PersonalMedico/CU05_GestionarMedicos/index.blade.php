@extends('layouts.app')

@section('title', 'Cuerpo médico | Clínica Vida Salud')

@section('content')
<div class="container clinica-module">
    <x-clinica-heading caso="Paquete 02 · CU05" title="Gestión del cuerpo médico" />
    <div class="clinica-actions">
        <a class="btn-primary" href="{{ route('medicos.index') }}#formulario">Nuevo médico</a>
    </div>
    <div class="clinica-table">
        <table>
            <thead><tr>
                <th scope="col">Médico</th><th scope="col">Matrícula</th><th scope="col">Especialidades</th>
                <th scope="col">Contacto</th><th scope="col">Estado</th><th scope="col">Acciones</th>
            </tr></thead>
            <tbody>
                @forelse ($medicos as $medico)
                    <tr>
                        <td>{{ $medico->nombre_completo }}</td>
                        <td>{{ $medico->registro_profesional }}</td>
                        <td>{{ $medico->especialidades->pluck('nombre_especialidad')->implode(', ') ?: 'Sin asignar' }}</td>
                        <td>{{ $medico->correo }}<br>{{ $medico->telefono }}</td>
                        <td><x-status-badge :type="$medico->estado ? 'stable' : 'caution'" :label="$medico->estado ? 'Activo' : 'Inactivo'" /></td>
                        <td><x-clinica-row-actions module="medicos" :record="$medico" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6">No se encontraron médicos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <section class="clinica-card" id="formulario">
        <h2>{{ $edicion ? 'Modificar médico' : 'Registrar nuevo médico' }}</h2>
        <x-form :action="$edicion ? route('medicos.update', $edicion) : route('medicos.store')" :method="$edicion ? 'PUT' : 'POST'" class="clinica-form">
            <div class="clinica-fields">
                <x-clinica-input name="nombre_completo" label="Nombre completo" :value="$edicion?->nombre_completo" maxlength="255" required />
                <x-clinica-input name="registro_profesional" label="Matrícula profesional" :value="$edicion?->registro_profesional" maxlength="100" required />
                <x-clinica-input name="correo" label="Correo electrónico" type="email" :value="$edicion?->correo" maxlength="255" />
                <x-clinica-input name="telefono" label="Teléfono de contacto" type="tel" :value="$edicion?->telefono" maxlength="50" />
            </div>
            <fieldset class="clinica-checks">
                <legend>Especialidades médicas</legend>
                @php($seleccionadas = $errors->any() ? old('especialidades', []) : ($edicion?->especialidades->modelKeys() ?? []))
                @forelse ($especialidades as $especialidad)
                    <label>
                        <input type="checkbox" name="especialidades[]" value="{{ $especialidad->id_especialidad }}"
                            @checked(in_array($especialidad->id_especialidad, (array) $seleccionadas))>
                        {{ $especialidad->nombre_especialidad }}{{ $especialidad->estado ? '' : ' (inactiva)' }}
                    </label>
                @empty
                    <p>No hay especialidades en el catálogo.</p>
                @endforelse
            </fieldset>
            <div class="clinica-actions">
                <button class="btn-primary" type="submit">Guardar médico</button>
                @if ($edicion)<a class="btn-outline" href="{{ route('medicos.index') }}">Cancelar edición</a>@endif
            </div>
        </x-form>
    </section>
</div>
@endsection

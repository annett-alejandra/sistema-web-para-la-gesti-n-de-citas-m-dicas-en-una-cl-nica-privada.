@extends('layouts.app')

@section('title', 'Fichas de pacientes | Clínica Vida Salud')

@section('content')
<div class="container clinica-module">
    <x-clinica-heading caso="Paquete 03 · CU07" title="Directorio y fichas de pacientes" />
    <x-form :action="route('pacientes.index')" method="GET" class="clinica-actions">
        <x-clinica-input name="q" label="Buscar por nombre o CI" type="search" :value="request()->query('q')" maxlength="255" />
        <button class="btn-outline" type="submit">Buscar</button>
        <a class="btn-primary" href="{{ route('pacientes.index') }}#formulario">Nueva ficha</a>
    </x-form>
    <div class="clinica-table">
        <table>
            <thead><tr>
                <th scope="col">CI</th><th scope="col">Nombre</th><th scope="col">Nacimiento y género</th>
                <th scope="col">Contacto</th><th scope="col">Estado</th><th scope="col">Acciones</th>
            </tr></thead>
            <tbody>
                @forelse ($pacientes as $paciente)
                    <tr>
                        <td>{{ $paciente->ci }}</td>
                        <td>{{ $paciente->nombre_completo }}</td>
                        <td>{{ $paciente->fecha_nacimiento?->format('d/m/Y') }} · {{ $paciente->genero }}</td>
                        <td>{{ $paciente->correo }}<br>{{ $paciente->telefono }}</td>
                        <td><x-status-badge :type="$paciente->estado ? 'stable' : 'caution'" :label="$paciente->estado ? 'Activo' : 'Inactivo'" /></td>
                        <td><x-clinica-row-actions module="pacientes" :record="$paciente" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6">No se encontraron pacientes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <section class="clinica-card" id="formulario">
        <h2>{{ $edicion ? 'Modificar ficha de paciente' : 'Registrar ficha de paciente' }}</h2>
        <x-form :action="$edicion ? route('pacientes.update', $edicion) : route('pacientes.store')" :method="$edicion ? 'PUT' : 'POST'" class="clinica-form">
            <div class="clinica-fields">
                <x-clinica-input name="ci" label="Cédula de identidad (CI)" :value="$edicion?->ci" maxlength="50" required />
                <x-clinica-input name="nombre_completo" label="Nombre completo" :value="$edicion?->nombre_completo" maxlength="255" required />
                <x-clinica-input name="fecha_nacimiento" label="Fecha de nacimiento" type="date" :value="$edicion?->fecha_nacimiento?->format('Y-m-d')" required />
                @php
                    $generos = ['Masculino' => 'Masculino', 'Femenino' => 'Femenino', 'Otro' => 'Otro'];
                    if ($edicion?->genero) {
                        $generos[$edicion->genero] = $edicion->genero;
                    }
                @endphp
                <x-clinica-select name="genero" label="Género" :options="$generos" :value="$edicion?->genero" required />
                <x-clinica-input name="correo" label="Correo electrónico" type="email" :value="$edicion?->correo" maxlength="255" />
                <x-clinica-input name="telefono" label="Teléfono" type="tel" :value="$edicion?->telefono" maxlength="50" />
                <x-clinica-input name="direccion_domicilio" label="Dirección de domicilio" :value="$edicion?->direccion_domicilio" maxlength="255" />
                <x-clinica-input name="contacto_emergencia" label="Contacto de emergencia" :value="$edicion?->contacto_emergencia" maxlength="255" />
                <x-clinica-input name="telefono_emergencia" label="Teléfono de emergencia" type="tel" :value="$edicion?->telefono_emergencia" maxlength="50" />
                <x-clinica-input name="tipo_paciente" label="Tipo de paciente" :value="$edicion?->tipo_paciente ?? 'General'" maxlength="100" />
                <x-clinica-input name="alergias" label="Alergias" type="textarea" :value="$edicion?->alergias" rows="2" />
                <x-clinica-input name="informacion_medica_basica" label="Información médica básica" type="textarea" :value="$edicion?->informacion_medica_basica" rows="2" />
            </div>
            <div class="clinica-actions">
                <button class="btn-primary" type="submit">Guardar ficha</button>
                @if ($edicion)<a class="btn-outline" href="{{ route('pacientes.index') }}">Cancelar edición</a>@endif
            </div>
        </x-form>
    </section>
</div>
@endsection

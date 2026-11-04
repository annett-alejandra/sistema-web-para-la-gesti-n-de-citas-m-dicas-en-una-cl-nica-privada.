@extends('layouts.app')

@section('title', 'Usuarios y roles | Clínica Vida Salud')

@section('content')
<div class="container clinica-module">
    <x-clinica-heading caso="Paquete 01 · CU03" title="Gestión de usuarios y roles"
        description="Administración de credenciales, roles y estados de acceso." />
    <div class="clinica-actions">
        <a class="btn-primary" href="{{ route('usuarios.index') }}#formulario">Nuevo usuario</a>
    </div>
    <div class="clinica-table">
        <table>
            <thead><tr>
                <th scope="col">ID</th><th scope="col">Usuario</th><th scope="col">Correo</th>
                <th scope="col">Rol</th><th scope="col">Estado</th><th scope="col">Acciones</th>
            </tr></thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id_usuario }}</td>
                        <td>{{ $usuario->nombre_usuario }}</td>
                        <td>{{ $usuario->correo }}</td>
                        <td>{{ $usuario->rol?->nombre_rol ?? 'Sin rol' }}</td>
                        <td><x-status-badge :type="$usuario->estado ? 'stable' : 'caution'" :label="$usuario->estado ? 'Activo' : 'Inactivo'" /></td>
                        <td><x-clinica-row-actions module="usuarios" :record="$usuario" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6">No se encontraron usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <section class="clinica-card" id="formulario">
        <h2>{{ $edicion ? 'Modificar usuario' : 'Registrar nuevo usuario' }}</h2>
        <x-form :action="$edicion ? route('usuarios.update', $edicion) : route('usuarios.store')" :method="$edicion ? 'PUT' : 'POST'" class="clinica-form">
            <div class="clinica-fields">
                <x-clinica-input name="nombre_usuario" label="Nombre de usuario" :value="$edicion?->nombre_usuario" maxlength="100" required />
                <x-clinica-input name="correo" label="Correo electrónico" type="email" :value="$edicion?->correo" maxlength="255" required />
                <x-clinica-input name="password" :label="$edicion ? 'Nueva contraseña (opcional)' : 'Contraseña temporal'"
                    type="password" minlength="6" maxlength="72" autocomplete="new-password" :required="!$edicion" />
                <x-clinica-select name="id_rol" label="Rol del sistema" :options="$roles->pluck('nombre_rol', 'id_rol')" :value="$edicion?->id_rol" required />
                @unless ($edicion)
                    <x-clinica-select name="id_paciente" label="Ficha de paciente (opcional)" :options="$pacientes->mapWithKeys(fn ($p) => [$p->id_paciente => $p->nombre_completo.' (CI '.$p->ci.')'])" />
                    <x-clinica-select name="id_medico" label="Ficha de médico (opcional)" :options="$medicos->pluck('nombre_completo', 'id_medico')" />
                @endunless
            </div>
            <div class="clinica-actions">
                <button class="btn-primary" type="submit">Guardar usuario</button>
                @if ($edicion)<a class="btn-outline" href="{{ route('usuarios.index') }}">Cancelar edición</a>@endif
            </div>
        </x-form>
    </section>
</div>
@endsection

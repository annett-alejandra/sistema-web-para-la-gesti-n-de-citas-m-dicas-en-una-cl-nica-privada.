@auth
    <div class="portal-nav">
        @can('portal')
            <nav aria-label="Módulos de la clínica">
                <a href="{{ route('panel') }}" @if(request()->routeIs('panel')) aria-current="page" @endif>Panel</a>
                <a href="{{ route('disponibilidad.index') }}" @if(request()->routeIs('disponibilidad.*')) aria-current="page" @endif>Disponibilidad</a>
                <a href="{{ route('citas.index') }}" @if(request()->routeIs('citas.*')) aria-current="page" @endif>Citas</a>
                @can('recepcion')
                    <a href="{{ route('pacientes.index') }}" @if(request()->routeIs('pacientes.*')) aria-current="page" @endif>Pacientes</a>
                    <a href="{{ route('medicos.index') }}" @if(request()->routeIs('medicos.*')) aria-current="page" @endif>Médicos</a>
                @endcan
                @can('horarios')
                    <a href="{{ route('horarios.index') }}" @if(request()->routeIs('horarios.*')) aria-current="page" @endif>Horarios</a>
                @endcan
                @can('administracion')
                    <a href="{{ route('especialidades.index') }}" @if(request()->routeIs('especialidades.*')) aria-current="page" @endif>Especialidades</a>
                    <a href="{{ route('usuarios.index') }}" @if(request()->routeIs('usuarios.*')) aria-current="page" @endif>Usuarios y roles</a>
                @endcan
            </nav>
        @endcan
        <x-form :action="route('logout')">
            <button class="btn-outline" type="submit">Cerrar sesión</button>
        </x-form>
    </div>
@else
    <nav class="portal-nav" aria-label="Acceso a la clínica">
        <a href="{{ route('login') }}">Iniciar sesión</a>
        <a href="{{ route('registro.index') }}">Crear cuenta de paciente</a>
    </nav>
@endauth

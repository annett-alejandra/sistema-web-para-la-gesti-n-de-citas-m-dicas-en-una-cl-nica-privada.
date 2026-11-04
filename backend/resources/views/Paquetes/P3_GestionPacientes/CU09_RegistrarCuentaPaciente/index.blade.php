@extends('layouts.app')

@section('title', 'Registro de paciente | Clínica Vida Salud')

@section('content')
<div class="container clinica-module clinica-narrow">
    <x-clinica-heading caso="Paquete 03 · CU09" title="Registro de cuenta de paciente"
        description="Complete sus datos para crear una cuenta y una ficha nueva. Si ya tiene ficha, contacte con admisiones para verificar su identidad." />
    <x-form :action="route('registro.store')" class="clinica-card clinica-form">
        <p>Podrá ingresar al portal cuando se habilite CU01.</p>
        <x-clinica-input name="ci" label="Cédula de identidad (CI)" maxlength="50" required />
        <x-clinica-input name="nombre_completo" label="Nombres y apellidos" maxlength="255" autocomplete="name" required />
        <div class="clinica-fields">
            <x-clinica-input name="fecha_nacimiento" label="Fecha de nacimiento" type="date" autocomplete="bday" required />
            <x-clinica-select name="genero" label="Género" :options="['Masculino' => 'Masculino', 'Femenino' => 'Femenino', 'Otro' => 'Otro']" required />
        </div>
        <x-clinica-input name="correo" label="Correo electrónico de acceso" type="email" maxlength="255" autocomplete="email" required />
        <x-clinica-input name="telefono" label="Teléfono" type="tel" maxlength="50" autocomplete="tel" />
        <x-clinica-input name="direccion_domicilio" label="Dirección de domicilio" maxlength="255" autocomplete="street-address" />
        <div class="clinica-fields">
            <x-clinica-input name="password" label="Contraseña" type="password" minlength="6" maxlength="72" autocomplete="new-password" required />
            <x-clinica-input name="password_confirmation" label="Confirme la contraseña" type="password" minlength="6" maxlength="72" autocomplete="new-password" required />
        </div>
        <label><input type="checkbox" name="terminos" value="1" @checked(old('terminos') === '1') required>
            Acepto el tratamiento de mis datos para crear mi cuenta y mi ficha en Clínica Vida Salud.
        </label>
        <div class="clinica-actions">
            <button class="btn-primary" type="submit">Registrar cuenta</button>
            <a class="btn-outline" href="{{ route('login') }}">Volver a iniciar sesión</a>
        </div>
    </x-form>
</div>
@endsection

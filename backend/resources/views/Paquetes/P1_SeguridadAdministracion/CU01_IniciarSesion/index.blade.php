@extends('layouts.app')

@section('title', 'Iniciar sesión | Clínica Vida Salud')

@section('content')
<div class="container clinica-module clinica-narrow">
    <x-clinica-heading caso="Paquete 01 · CU01" title="Iniciar sesión" />
    <section class="clinica-card">
        <p id="acceso-pendiente">El acceso al portal se habilitará al integrar CU01 con la sesión de Laravel.</p>
        <fieldset class="clinica-form" aria-describedby="acceso-pendiente" disabled>
            <legend>Credenciales de acceso</legend>
            <x-clinica-input name="identificador" label="Correo electrónico o usuario" autocomplete="username" required />
            <x-clinica-input name="password" label="Contraseña" type="password" autocomplete="current-password" required />
            <button type="button" class="btn-primary">Ingresar</button>
        </fieldset>
        <a class="btn-outline" href="{{ route('registro.index') }}">Crear cuenta de paciente</a>
    </section>
</div>
@endsection

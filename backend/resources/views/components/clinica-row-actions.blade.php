@props(['module', 'record'])

<div class="clinica-actions">
    <a class="btn-outline" href="{{ route($module.'.index', ['editar' => $record->getKey()]) }}#formulario">Modificar</a>
    <x-form :action="route($module.'.estado', $record)" method="PATCH">
        <button class="btn-outline" type="submit">{{ $record->estado ? 'Desactivar' : 'Activar' }}</button>
    </x-form>
</div>

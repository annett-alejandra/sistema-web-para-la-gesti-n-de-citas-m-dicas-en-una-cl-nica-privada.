@props(['name', 'label', 'options', 'value' => null, 'placeholder' => 'Seleccione…', 'useOld' => true])

@php
    $current = $useOld ? old($name, $value) : $value;
    $current = is_scalar($current) ? (string) $current : '';
@endphp

<label class="clinica-field">
    <span>{{ $label }}</span>
    <select name="{{ $name }}" {{ $attributes }}>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $key => $description)
            <option value="{{ $key }}" @selected((string) $key === $current)>{{ $description }}</option>
        @endforeach
    </select>
</label>

@props(['name', 'label', 'value' => null, 'type' => 'text'])

@php
    $current = $type === 'password' ? '' : old($name, $value);
    $current = is_scalar($current) ? $current : '';
@endphp

<label class="clinica-field">
    <span>{{ $label }}</span>
    @if ($type === 'textarea')
        <textarea name="{{ $name }}" {{ $attributes }}>{{ $current }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" value="{{ $current }}" {{ $attributes }}>
    @endif
</label>

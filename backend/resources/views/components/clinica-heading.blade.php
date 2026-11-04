@props(['caso', 'title', 'description' => null])

<header class="clinica-heading">
    <span class="editorial-overline">{{ $caso }}</span>
    <h1 class="font-display">{{ $title }}</h1>
    @if ($description)
        <p>{{ $description }}</p>
    @endif
</header>

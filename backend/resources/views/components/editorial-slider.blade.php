@props(['slides' => []])

@if ($slides)
    <div class="slider-container" data-slider>
        <div class="slider-viewport">
            @foreach ($slides as $slide)
                <article class="slider-slide" data-slide data-location="{{ $slide['location'] }}" @if(!$loop->first) hidden @endif>
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="slider-img" loading="lazy" decoding="async">
                    <div class="slider-caption">
                        <span class="editorial-overline">{{ $slide['category'] }}</span>
                        <h3 class="font-display">{{ $slide['title'] }}</h3>
                        <p>{{ $slide['description'] }}</p>
                        <p class="font-mono">{{ $slide['specs'] }}</p>
                        <a href="{{ $slide['image'] }}" class="btn-outline" data-gallery-item="{{ json_encode($slide, JSON_THROW_ON_ERROR) }}">Ampliar imagen</a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="slider-controls" data-slider-controls hidden>
            <p class="slider-counter" aria-live="polite">
                <strong data-slide-number>01</strong> / {{ str_pad(count($slides), 2, '0', STR_PAD_LEFT) }}
                <span data-slide-location>{{ $slides[0]['location'] }}</span>
            </p>
            <div class="slider-arrows">
                <button type="button" class="arrow-btn" data-slide-step="-1" aria-label="Diapositiva anterior">←</button>
                <button type="button" class="arrow-btn" data-slide-step="1" aria-label="Siguiente diapositiva">→</button>
            </div>
        </div>
    </div>
@endif

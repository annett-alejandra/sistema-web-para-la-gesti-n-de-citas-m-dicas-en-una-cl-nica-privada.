@extends('layouts.app')

@section('title', 'Clínica Vida Salud — Excelencia Médica & Hospitalidad Terapéutica')

@section('content')

@php
    $heroSlides = [
        [
            'title' => 'Pabellón Quirúrgico Robótico Integrado',
            'category' => 'Cirugía de Alta Precisión',
            'image' => 'https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&w=1280&q=75',
            'specs' => 'Flujo Laminar ISO 5 · Sistema Da Vinci Xi',
            'description' => 'Quirófano inteligente equipado con telemetría en tiempo real y asistencia microquirúrgica guiada por fluorescencia.',
            'location' => 'Nivel 02 · Ala Quirúrgica Norte',
        ],
        [
            'title' => 'Suites Terapéuticas con Jardín Interior',
            'category' => 'Hospitalización Premium',
            'image' => 'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?auto=format&fit=crop&w=1280&q=75',
            'specs' => 'Control Circadiano · Aislamiento 48dB',
            'description' => 'Habitaciones privadas que integran luz natural biofílica para acelerar los tiempos fisiológicos de recuperación.',
            'location' => 'Nivel 04 · Pabellón de Reposo',
        ],
        [
            'title' => 'Unidad de Resonancia Magnética 3.0 Tesla',
            'category' => 'Diagnóstico Avanzado',
            'image' => 'https://images.unsplash.com/photo-1516549655169-df83a0774514?auto=format&fit=crop&w=1280&q=75',
            'specs' => 'Túnel Ultra-Ancho · Audio Ambient',
            'description' => 'Adquisición espectrométrica no invasiva con resolución submilenaria para estudios neurológicos y cardiológicos.',
            'location' => 'Nivel 01 · Centro de Imágenes',
        ],
        [
            'title' => 'Atrio Central y Jardines de Sanación',
            'category' => 'Arquitectura Sanitaria',
            'image' => 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1280&q=75',
            'specs' => 'Ventilación Natural Cruzada',
            'description' => 'Espacios concebidos para mitigar los estresores hospitalarios tradicionales mediante vegetación nativa y luz cenital.',
            'location' => 'Planta Baja · Área Social',
        ],
        [
            'title' => 'Centro de Monitoreo Hemodinámico 24/7',
            'category' => 'Cuidados Intensivos',
            'image' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1280&q=75',
            'specs' => 'Ratio 1:1 · Presión Negativa',
            'description' => 'Plataforma unificada de telemetría cardiovascular con predicción algorítmica de signos vitales críticos.',
            'location' => 'Nivel 03 · Cuidados Críticos',
        ],
        [
            'title' => 'Laboratorio de Genómica y Medicina Molecular',
            'category' => 'Investigación Clínica',
            'image' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?auto=format&fit=crop&w=1280&q=75',
            'specs' => 'Secuenciación NGS de Alta Tasa',
            'description' => 'Biomarcadores tumorales individualizados para terapias oncológicas dirigidas de máxima efectividad.',
            'location' => 'Nivel Subterráneo · Biología Molecular',
        ],
        [
            'title' => 'Módulo de Hidroterapia y Fisioterapia Asistida',
            'category' => 'Rehabilitación Integral',
            'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1280&q=75',
            'specs' => 'Piscina de Marcha Inversa',
            'description' => 'Recuperación neuromuscular acelerada en entorno hídrico termorregulado con apoyo cinético continuo.',
            'location' => 'Nivel 01 · Medicina Física',
        ],
        [
            'title' => 'Salón de Consultas y Medicina Preventiva',
            'category' => 'Consulta Externa',
            'image' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=1280&q=75',
            'specs' => 'Privacidad Total · Digital',
            'description' => 'Atención médica cercana y personalizada en un ambiente cálido que elimina la frialdad instrumental.',
            'location' => 'Nivel 02 · Consultorios Médicos',
        ]
    ];
    $protocolos = [
        [
            'num' => '01',
            'title' => 'El Diagnóstico',
            'tag' => 'EVALUACIÓN INTEGRAL Y BIOMARCADORES',
            'desc' => 'Cada paciente ingresa a través de un panel multidisciplinario que evalúa antecedentes, factores epigenéticos y telemetría no invasiva antes de definir cualquier plan terapéutico.',
            'quote' => '«No tratamos diagnósticos aislados; comprendemos la singularidad biológica de cada persona.»',
            'doctor' => 'Dr. Alejandro Vaca Díez · Jefe de Medicina Interna',
            'image' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=700&q=75',
            'checklist' => [
                'Secuenciación genómica y perfiles metabólicos precisos',
                'Monitoreo hemodinámico inalámbrico y continuo',
                'Ateneo médico diario de discusión clínica'
            ]
        ],
        [
            'num' => '02',
            'title' => 'El Procedimiento',
            'tag' => 'CIRUGÍA MÍNIMAMENTE INVASIVA Y ROBÓTICA',
            'desc' => 'Utilizamos brazos robóticos con capacidad de giro de 540 grados y visualización tridimensional de altísima resolución, reduciendo el sangrado operatorio a niveles mínimos.',
            'quote' => '«La precisión micrométrica nos permite preservar el tejido sano y garantizar altas tempranas.»',
            'doctor' => 'Dra. Marcela Justiniano · Cirujana Robótica Principal',
            'image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=700&q=75',
            'checklist' => [
                'Abordajes laparoscópicos de incisión única',
                'Quirófanos con flujo laminar estéril ISO Clase 5',
                'Seguimiento anestésico por electroencefalograma'
            ]
        ],
        [
            'num' => '03',
            'title' => 'La Recuperación',
            'tag' => 'REHABILITACIÓN EN SUITES BIOFÍLICAS',
            'desc' => 'El proceso postoperatorio inicia desde las primeras horas con fisioterapia asistida, nutrición clínica personalizada diseñada por chefs hospitalarios y acompañamiento emocional.',
            'quote' => '«El descanso sereno y la nutrición idónea son tan determinantes como el acto quirúrgico.»',
            'doctor' => 'Lic. Sofía Ribera · Directora de Cuidados Postoperatorios',
            'image' => 'https://images.unsplash.com/photo-1594824813590-b18485223385?auto=format&fit=crop&w=700&q=75',
            'checklist' => [
                'Kinesioterapia en piscinas de hidroterapia térmica',
                'Nutrición enteral orgánica adaptada a la patología',
                'Control ambulatorio continuo mediante telemedicina'
            ]
        ]
    ];
    $espacios = [
        [
            'title' => 'Quirófano Híbrido 01',
            'category' => 'Quirófanos',
            'image' => 'https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&w=700&q=75',
            'specs' => 'Flujo Laminar Clase 100',
            'location' => 'Ala Quirúrgica · Piso 2',
            'description' => 'Quirófano con arco en C robotizado para cirugías cardiovasculares complejas.'
        ],
        [
            'title' => 'Suite Imperial de Recuperación',
            'category' => 'Hospitalización',
            'image' => 'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?auto=format&fit=crop&w=700&q=75',
            'specs' => 'Vista Panorámica · 60m²',
            'location' => 'Torre Médica · Piso 4',
            'description' => 'Suite privada con sala de visitas separada y gastronomía clínica personalizada.'
        ],
        [
            'title' => 'Tomografía Computarizada Espectral',
            'category' => 'Diagnóstico por Imagen',
            'image' => 'https://images.unsplash.com/photo-1516549655169-df83a0774514?auto=format&fit=crop&w=700&q=75',
            'specs' => 'Doble Fuente 256 Cortes',
            'location' => 'Centro de Diagnóstico · Nivel 1',
            'description' => 'Detección temprana con mínima dosis de contraste y reconstrucción 3D inmediata.'
        ],
        [
            'title' => 'Jardín Zen Botánico Curativo',
            'category' => 'Jardines Terapéuticos',
            'image' => 'https://images.unsplash.com/photo-1584467735871-8e85353a8413?auto=format&fit=crop&w=700&q=75',
            'specs' => 'Flora Nativa Oxigenante',
            'location' => 'Atrio Central · Planta Baja',
            'description' => 'Entorno natural accesible con senderos sensoriales para paseos de recuperación.'
        ],
        [
            'title' => 'Quirófano Robótico Da Vinci',
            'category' => 'Quirófanos',
            'image' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=700&q=75',
            'specs' => 'Consola Doble Cirujano',
            'location' => 'Ala Quirúrgica · Piso 2',
            'description' => 'Tecnología laparoscópica avanzada para procedimientos urológicos y ginecológicos.'
        ],
        [
            'title' => 'Cuidados Intensivos Coronarios',
            'category' => 'Hospitalización',
            'image' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=700&q=75',
            'specs' => 'Monitoreo 24/7',
            'location' => 'Unidad Coronaria · Piso 3',
            'description' => 'Boxes individuales con aislamiento vírico bacteriano y telemetría de última generación.'
        ]
    ];
@endphp

<!-- =========================================================================
     00. APERTURA E INTRO INMERSIVA (HERO SECTION OPTIMIZADA)
     ========================================================================= -->
<section class="portada-hero">
    <div class="container portada-hero-meta">
        <span class="editorial-overline">Hospital privado · Excelencia quirúrgica</span>
        <x-status-badge type="stable" label="Operación Continua 24/7" />
        <a href="#capitulo-01" class="btn-minimal">Saltar intro ↗</a>
    </div>
    <div class="container portada-hero-copy">
        <p class="editorial-overline">Capítulo Proemio · El Arte del Cuidado Clínico</p>
        <h1 class="font-display">La medicina de <em>precisión</em> encuentra su lugar más sereno.</h1>
        <p>Redefinimos la experiencia hospitalaria integrando tecnología quirúrgica de vanguardia, confort biofílico y un modelo de atención centrado en la dignidad humana.</p>
        <div class="clinica-actions">
            <a href="#admisiones" class="btn-primary">Programar evaluación médica</a>
            <a href="#capitulo-01" class="btn-outline">Explorar el complejo</a>
        </div>
    </div>
    <div class="container portada-hero-meta">
        <span class="editorial-overline">Explorar capítulos ↓</span>
        <span class="font-mono">-17°46'48"S · -63°11'02"W · Santa Cruz, Bolivia</span>
    </div>
</section>

<!-- =========================================================================
     CAPÍTULO 01: INFRAESTRUCTURA Y ESPACIOS TERAPÉUTICOS
     ========================================================================= -->
<x-section-chapter
    id="capitulo-01"
    number="01"
    overline="EL COMPLEJO HOSPITALARIO"
    title="Espacios concebidos para la serenidad y la "
    italic="máxima precisión."
    subtitle="Una arquitectura de luz cenital, materiales nobles y aislamiento acústico que transforma el entorno clínico en un catalizador biológico de bienestar."
>
    <!-- Slider Editorial Interactivo con Contador 01 / 08 -->
    <x-editorial-slider :slides="$heroSlides" />

    <!-- Datos Arquitectónicos Complementarios -->
    <div class="portada-grid portada-tres portada-separacion">
        <div class="clinica-card">
            <span class="editorial-overline">BIOFILIA ACTIVA</span>
            <h4 class="font-display">Luz Circadiana Integrada</h4>
            <p>
                Graduación cromática sincronizada con el ciclo solar para reducir el estrés del paciente y estimular la producción natural de melatonina.
            </p>
        </div>

        <div class="clinica-card">
            <span class="editorial-overline">AIRE ULTRA-PURO</span>
            <h4 class="font-display">Presión Positiva & HEPA H14</h4>
            <p>
                Recambio completo del volumen de aire cada 90 segundos con 99.995% de retención de micropartículas y patógenos aerotransportados.
            </p>
        </div>

        <div class="clinica-card">
            <span class="editorial-overline">AISLAMIENTO</span>
            <h4 class="font-display">Acústica Silenciosa</h4>
            <p>
                Paneles fonoabsorbentes y doble tabiquería que garantizan un umbral inferior a 35 decibelios en todas las suites de hospitalización.
            </p>
        </div>
    </div>
</x-section-chapter>

<!-- =========================================================================
     CAPÍTULO 02: UNIDADES DE ALTA COMPLEJIDAD Y CONFORT
     ========================================================================= -->
<x-section-chapter
    id="capitulo-02"
    number="02"
    overline="UNIDADES CLÍNICAS & SUITES"
    title="La intimidad de una residencia privada, la seguridad de una "
    italic="unidad intensiva."
    subtitle="Equipamiento de última generación integrado de forma invisible en suites diseñadas para acoger a la familia y otorgar confort absoluto durante la estancia médica."
>
    <!-- Métricas Numéricas de Gran Impacto (Display Grande) -->
    <div class="portada-grid portada-cuatro portada-metricas">
        <div class="metric-box">
            <div>
                <x-status-badge type="stable" label="Disponibilidad Inmediata" />
                <div class="metric-number">32<span> Suites</span></div>
                <div class="metric-label">Hospitalización VIP</div>
            </div>
            <p class="metric-desc">Ambientes privados de 55m² con antesala para acompañantes y balcón botánico.</p>
        </div>

        <div class="metric-box">
            <div>
                <x-status-badge type="info" label="Quirófanos Activos" />
                <div class="metric-number">06<span> Salas</span></div>
                <div class="metric-label">Quirófanos Híbridos</div>
            </div>
            <p class="metric-desc">Integración tomográfica peroperatoria y robótica asistida de alta resolución.</p>
        </div>

        <div class="metric-box">
            <div>
                <x-status-badge type="accent" label="Protocolo Exclusivo" />
                <div class="metric-number">1:1<span> Ratio</span></div>
                <div class="metric-label">Atención Médica</div>
            </div>
            <p class="metric-desc">Asignación de enfermería especializada individual para pacientes de alta complejidad.</p>
        </div>

        <div class="metric-box">
            <div>
                <x-status-badge type="stable" label="Control Microbiológico" />
                <div class="metric-number">0.02%</div>
                <div class="metric-label">Tasa de Infección</div>
            </div>
            <p class="metric-desc">Índice intrahospitalario muy por debajo de los estándares mundiales más exigentes.</p>
        </div>
    </div>

    <!-- Malla de Unidades de Atención -->
    <div class="portada-grid portada-dos portada-separacion">
        <article class="editorial-card">
            <div class="portada-image">
                <img
                    src="https://images.unsplash.com/photo-1512678080530-7760d81faba6?auto=format&fit=crop&w=800&q=75"
                    alt="Suite Médica Privada"
                    loading="lazy"
                    decoding="async"
                />
                <div class="portada-image-badge">
                    <x-status-badge type="stable" label="Suite Presidencial" />
                </div>
            </div>
            <div class="portada-card-copy">
                <span class="editorial-overline">SUITE CLÍNICA PREMIUM</span>
                <h3 class="font-display">Alojamiento Terapéutico de Lujo</h3>
                <p>
                    Mobiliario médico motorizado recubierto en maderas sustentables, domótica táctil para control ambiental, cama king size para acompañante y baño privado en mármol antideslizante.
                </p>
                <div class="portada-card-specs font-mono">
                    <span>SUPERFICIE: 65 M²</span>
                    <span>BAÑO EN SUITE · CHEF PRIVADO</span>
                </div>
            </div>
        </article>

        <article class="editorial-card">
            <div class="portada-image">
                <img
                    src="https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&w=800&q=75"
                    alt="Centro de Diagnóstico por Imágenes"
                    loading="lazy"
                    decoding="async"
                />
                <div class="portada-image-badge">
                    <x-status-badge type="info" label="Tecnología 2026" />
                </div>
            </div>
            <div class="portada-card-copy">
                <span class="editorial-overline">IMAGENOLOGÍA AVANZADA</span>
                <h3 class="font-display">Centro de Diagnóstico Molecular</h3>
                <p>
                    Tomografía computarizada espectral con reducción de radiación del 80%, resonancia magnética funcional y ecografía Doppler con reconstrucción volumétrica en tiempo real.
                </p>
                <div class="portada-card-specs font-mono">
                    <span>INFORME EN &lt; 60 MIN</span>
                    <span>INTELIGENCIA CLÍNICA AI</span>
                </div>
            </div>
        </article>
    </div>
</x-section-chapter>

<!-- =========================================================================
     CAPÍTULO 03: ESPECIALIDADES MÉDICAS Y ENFOQUE HUMANO
     ========================================================================= -->
<x-section-chapter
    id="capitulo-03"
    number="03"
    overline="VOCACIÓN MÉDICA & PROTOCOLOS"
    title="Equipos de élite dedicados a cada instante de su "
    italic="recuperación integral."
    subtitle="Un claustro médico conformado por especialistas acreditados internacionalmente, unidos bajo una filosofía de atención cercana, empática y rigurosamente científica."
>
    <section class="clinica-card" data-protocols>
        <div class="clinica-actions" data-protocol-controls hidden aria-label="Protocolos de atención">
            @foreach ($protocolos as $tab)
                <button class="tab-pill" type="button" data-protocol-button aria-controls="protocolo-{{ $loop->index }}">
                    {{ $tab['num'] }} · {{ $tab['title'] }}
                </button>
            @endforeach
        </div>
        @foreach ($protocolos as $tab)
            <article class="protocol-panel" id="protocolo-{{ $loop->index }}" data-protocol-panel>
                <div>
                    <span class="editorial-overline">{{ $tab['tag'] }}</span>
                    <h3 class="font-display">{{ $tab['title'] }}</h3>
                    <p>{{ $tab['desc'] }}</p>
                    <ul>
                        @foreach ($tab['checklist'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    <blockquote>
                        <p class="font-display">{{ $tab['quote'] }}</p>
                        <footer>{{ $tab['doctor'] }}</footer>
                    </blockquote>
                </div>
                <img src="{{ $tab['image'] }}" alt="{{ $tab['title'] }}" loading="lazy" decoding="async">
            </article>
        @endforeach
    </section>
</x-section-chapter>

<!-- =========================================================================
     CAPÍTULO 04: EXPLORADOR COMPLETO DE INSTALACIONES (GALLERY EXPLORER)
     ========================================================================= -->
<x-section-chapter
    id="capitulo-04"
    number="04"
    overline="EXPLORADOR DE INSTALACIONES"
    title="Arquitectura de vanguardia al servicio de la "
    italic="salud y la calma."
    subtitle="Recorra visualmente nuestras áreas clínicas, unidades de imagenología y jardines terapéuticos diseñados con los más rigurosos estándares sanitarios internacionales."
>
    <div data-gallery>
        <div class="clinica-actions gallery-filters" data-gallery-filters hidden>
            <button class="btn-outline" type="button" data-gallery-filter="" aria-pressed="true">Todas</button>
            @foreach (array_unique(array_column($espacios, 'category')) as $categoria)
                <button class="btn-outline" type="button" data-gallery-filter="{{ $categoria }}" aria-pressed="false">{{ $categoria }}</button>
            @endforeach
        </div>
        <p class="gallery-count" aria-live="polite"><span data-gallery-count>{{ count($espacios) }}</span> espacios</p>
        <div class="portada-grid portada-tres">
            @foreach ($espacios as $espacio)
                <article class="editorial-card" data-gallery-category="{{ $espacio['category'] }}">
                    <div class="portada-image">
                        <img src="{{ $espacio['image'] }}" alt="{{ $espacio['title'] }}" loading="lazy" decoding="async">
                        <span class="editorial-overline">{{ $espacio['category'] }}</span>
                    </div>
                    <div class="portada-card-copy">
                        <small>{{ $espacio['location'] }}</small>
                        <h3 class="font-display">{{ $espacio['title'] }}</h3>
                        <p>{{ $espacio['description'] }}</p>
                        <p class="font-mono">{{ $espacio['specs'] }}</p>
                        <a href="{{ $espacio['image'] }}" class="btn-outline" data-gallery-item="{{ json_encode($espacio, JSON_THROW_ON_ERROR) }}">Ver instalación</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</x-section-chapter>

<!-- =========================================================================
     FOOTER EDITORIAL: ADMISIONES, COORDINACIÓN Y CONTACTO
     ========================================================================= -->
<footer id="admisiones" class="site-footer">
    <div class="container">
        <!-- Bloque Principal de Coordinación Médica -->
        <div class="portada-footer-intro">
            <span class="editorial-overline">COORDINACIÓN DE ADMISIONES & CITAS</span>
            <h2 class="font-display">
                Consulte con el equipo de admisiones médicas.
            </h2>
            <p>
                Nuestro departamento de coordinación clínica le orientará sobre especialistas, preparación para procedimientos quirúrgicos y coberturas médicas nacionales e internacionales.
            </p>

            <div class="clinica-actions">
                <a href="tel:+59133445566" class="btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span>Línea Directa: +591 (3) 344-5566</span>
                </a>

                <a href="mailto:admisiones@clinicavidasalud.com.bo" class="btn-outline">
                    <span>admisiones@clinicavidasalud.com.bo</span>
                </a>
            </div>
        </div>

        <!-- Directorio y Certificaciones Sanitarias -->
        <div class="portada-grid portada-cuatro portada-footer-info">
            <div>
                <span class="font-display">Clínica Vida Salud</span>
                <p>
                    Institución hospitalaria de tercer nivel especializada en cirugía de alta complejidad, medicina cardiovascular y diagnóstico molecular.
                </p>
                <div>
                    <x-status-badge type="stable" label="Acreditación JCI Internacional" />
                </div>
            </div>

            <div>
                <span class="editorial-overline">UBICACIÓN</span>
                <p>
                    Avenida San Martín #1840, Zona Equipetrol<br>
                    Santa Cruz de la Sierra, Bolivia
                </p>
                <span class="font-mono">Heliopuerto Clínico H-02</span>
            </div>

            <div>
                <span class="editorial-overline">HORARIOS</span>
                <ul>
                    <li class="portada-footer-hours">
                        <span>Urgencias & UCI:</span>
                        <span class="font-mono">24 HORAS</span>
                    </li>
                    <li class="portada-footer-hours">
                        <span>Consulta Externa:</span>
                        <span>07:00 – 21:00</span>
                    </li>
                    <li class="portada-footer-hours">
                        <span>Imágenes:</span>
                        <span>06:30 – 22:00</span>
                    </li>
                </ul>
            </div>

            <div>
                <span class="editorial-overline">CERTIFICACIONES</span>
                <p>
                    Certificación de Calidad ISO 9001:2015 en Gestión Hospitalaria y Normativa de Bioseguridad Grado Médico Clase A.
                </p>
                <div class="font-mono">
                    REGISTRO SEDES: N° 4492-SCZ
                </div>
            </div>
        </div>

        <!-- Pie Legal y Derechos -->
        <div class="portada-footer-legal">
            <p>© {{ date('Y') }} Clínica Vida Salud S.A. Todos los derechos reservados.</p>
            <div class="clinica-actions">
                <a href="#">Privacidad Clínica</a>
                <a href="#">Consentimiento Informado</a>
            </div>
        </div>
    </div>
</footer>

<x-gallery-modal />
@endsection

@push('styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&amp;family=Plus+Jakarta+Sans:wght@400;600;700&amp;family=JetBrains+Mono&amp;display=swap">
    <link rel="stylesheet" href="{{ asset('css/portada.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/portada.js') }}" defer></script>
@endpush

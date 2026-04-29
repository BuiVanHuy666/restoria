@props([
    'title',
    'image' => asset('images/background/banner-image-1.jpg')
])

<section class="inner-banner">
    <div class="image-layer" style="background-image: url('{{ $image }}');"></div>
    <div class="auto-container">
        <div class="inner">
            <h1>{{ $title }}</h1>
            <div class="sub_text">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>

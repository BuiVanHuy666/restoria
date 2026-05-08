<x-layouts.app title="Thư viện ảnh">
    @php
        $allGalleries = \App\Models\Gallery::where('is_active', true)->latest()->get();
    @endphp

    <x-partials.inner-banner
        title="Thư viện ảnh"
        :image="asset('images/background/banner-image-4.jpg')"
    >
        <p>Hương vị hoàn hảo trong từng món ăn - <span class="primary-color">ẩm thực cao cấp mang hơi thở hiện đại.</span></p>
    </x-partials.inner-banner>

    <section class="gallery-page section-kt">
        <div class="auto-container">
            <div class="tabs-box">
                <div class="menu-tabs">
                    <div class="buttons">
                        <ul class="tab-buttons clearfix">
                            @foreach(\App\Models\Gallery::GALLERY_TYPES as $key => $label)
                                <li class="tab-btn {{ $loop->first ? 'active-btn' : '' }}" data-tab="#gallery-{{ $key }}">
                                    {{ $label }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="tabs-content">
                    @foreach(\App\Models\Gallery::GALLERY_TYPES as $key => $label)
                        <div class="tab {{ $loop->first ? 'active-tab' : '' }}" id="gallery-{{ $key }}">
                            <div class="masonry">
                                @forelse($allGalleries->where('category', $key) as $item)
                                    <div class="masonry-item">
                                        <a href="{{ asset('storage/' . $item->image_path) }}"
                                           class="fancybox"
                                           data-fancybox="{{ $key }}"
                                           data-caption="{{ $item->title }}">
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}">
                                        </a>
                                    </div>
                                @empty
                                    <div class="text-center py-10 text-zinc-400">Đang cập nhật hình ảnh...</div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

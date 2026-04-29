<x-layouts.app title="Thư viện ảnh">
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
                            <li class="tab-btn active-btn" data-tab="#gallery-space">Không gian nhà hàng</li>
                            <li class="tab-btn" data-tab="#gallery-food">Thức ăn & Thức uống</li>
                            <li class="tab-btn" data-tab="#gallery-guests">Thực khách</li>
                        </ul>
                    </div>
                </div>

                <div class="tabs-content">
                    <div class="tab active-tab" id="gallery-space">
                        <div class="masonry">
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic1.jpg') }}" class="fancybox" data-fancybox="space">
                                    <img src="{{ asset('images/gallery/pic1-thumb.jpg') }}" alt="Không gian">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic2.jpg') }}" class="fancybox" data-fancybox="space">
                                    <img src="{{ asset('images/gallery/pic1-thumb.jpg') }}" alt="Không gian">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic3.jpg') }}" class="fancybox" data-fancybox="space">
                                    <img src="{{ asset('images/gallery/pic1-thumb.jpg') }}" alt="Không gian">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic4.jpg') }}" class="fancybox" data-fancybox="space">
                                    <img src="{{ asset('images/gallery/pic1-thumb.jpg') }}" alt="Không gian">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="tab" id="gallery-food">
                        <div class="masonry">
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic2.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic5.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div><div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic4.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div><div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic1.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div><div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic10.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="tab" id="gallery-guests">
                        <div class="masonry">
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic3.jpg') }}" class="fancybox" data-fancybox="guests">
                                    <img src="{{ asset('images/gallery/pic3-thumb.jpg') }}" alt="Thực khách">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic2.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic1.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic1-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic4.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic1-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic5.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic5-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic6.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic7.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic8.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                            <div class="masonry-item">
                                <a href="{{ asset('images/gallery/pic9.jpg') }}" class="fancybox" data-fancybox="food">
                                    <img src="{{ asset('images/gallery/pic2-thumb.jpg') }}" alt="Thức ăn">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

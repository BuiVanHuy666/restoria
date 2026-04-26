<section class="special-offer section-kt">
    <div class="outer-container">
        <div class="auto-container">
            <div class="title-box centered">
                <div class="subtitle"><span>Phổ biến</span></div>
                <h2>Món Ăn Đặc Sắc</h2>
            </div>

            <div class="dish-gallery-slider owl-theme owl-carousel">
                @foreach($popularItems as $item)
                    <div class="offer-block-two {{ $item->is_round_image ? 'rounded' : '' }}">
                        <div class="inner-box">
                            <div class="cat-name">{{ $item->categories->first()->name ?? 'Món ăn' }}</div>
                            <div class="image">
                                <a href="#">
                                    <img src="{{ $item->thumbnailUrl }}"
                                         class="{{ $item->is_round_image ? 'rounded-full aspect-square object-cover' : '' }}"
                                         alt="{{ $item->name }}"
                                         loading="lazy"
                                    >
                                </a>
                            </div>
                            <h5><a href="#">{{ $item->name }}</a></h5>
                            <div class="text desc">{{ Str::limit($item->description, 80) }}</div>
                            <div class="price">{{ number_format($item->price) }}đ</div>
                        </div>
                    </div>
                @endforeach
            </div>

            ...
        </div>
    </div>
</section>

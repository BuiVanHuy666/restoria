<section class="menu-card-style-section section-kt">
    <div class="auto-container">
        <div class="menu-card-main">
            <div class="top-pattern"></div>
            <div class="title-box centered">
                <div class="title-badge"><img src="{{ asset('images/resource/menu-title-badge.svg') }}" alt="thực đơn"></div>
                <h2>Thực Đơn Của Chúng Tôi</h2>
            </div>

            @foreach($categoriesWithItems as $category)
                @if($category->menuItems->count() > 0)
                    <div class="menu-card-style {{ $loop->odd ? 'alternate' : '' }} {{ $loop->last ? 'last' : '' }}">
                        <div class="row clearfix">
                            <div class="image-col col-lg-6 col-md-12 col-sm-12">
                                <div class="inner">
                                    <div class="image">
                                        <img src="{{ $category->thumbnail_url ?? asset('images/resource/appetizers1.jpg') }}" alt="{{ $category->name }}" loading="lazy">
                                    </div>
                                </div>
                            </div>

                            <div class="menu-col col-lg-6 col-md-12 col-sm-12">
                                <div class="inner">
                                    <div class="title-box">
                                        <h3>{{ $category->name }}</h3>
                                    </div>

                                    @foreach($category->menuItems as $item)
                                        <div class="dish-block">
                                            <div class="inner-box">
                                                <div class="title clearfix">
                                                    <div class="ttl clearfix">
                                                        <h6>
                                                            <a href="#">{{ $item->name }}</a>
                                                            @if($item->is_new)
                                                                <span class="s-info">mới</span>
                                                            @endif
                                                        </h6>
                                                    </div>
                                                    <span class="menu-list-line"> </span>
                                                    <div class="price"><span>{{ number_format($item->price) }}đ</span></div>
                                                </div>
                                                <div class="text desc">
                                                    <a href="#">{{ $item->description }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
</section>

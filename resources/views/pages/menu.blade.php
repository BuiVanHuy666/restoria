<x-layouts.app title="Thực đơn nhà hàng">
    <x-partials.inner-banner
        title="Thực Đơn Nhà Hàng"
        :image="asset('images/background/banner-image-2.jpg')"
    >
        <p>Hương vị hoàn hảo trong từng món ăn -
            <span class="primary-color"> ẩm thực cao cấp mang hơi thở hiện đại.</span></p>
    </x-partials.inner-banner>

    @foreach($categories as $category)
        <section class="menu-one {{ $loop->odd ? 'alternate section-kt' : '' }}">
            <div class="auto-container">
                <div class="row clearfix">

                    <div class="image-col col-lg-6 col-md-12 col-sm-12">
                        <div class="inner">
                            <div class="vertical-title"> {{ $category->name }} </div>
                            <div class="image">
                                <img src="{{ $category->thumbnail_url }}" alt="{{ $category->name }}" loading="lazy">
                            </div>
                        </div>
                    </div>

                    <div class="menu-col col-lg-6 col-md-12 col-sm-12">
                        <div class="inner">
                            <div class="title-box">
                                <h3>{{ $category->name }}</h3>
                            </div>

                            @forelse($category->menuItems as $item)
                                <div class="dish-block">
                                    <div class="inner-box">
                                        <div class="title clearfix">
                                            <div class="ttl clearfix">
                                                <h6>
                                                    <a href="#">{{ $item->name }}</a>

                                                    <div class="inline-flex gap-1 ml-2">
                                                        @if($item->is_popular)
                                                            <span class="s-info bg-orange-500! text-white! text-[10px]! uppercase px-2 py-0.5 rounded">Bán chạy</span>
                                                        @endif

                                                        @if($item->is_new)
                                                            <span class="s-info bg-emerald-600! text-white! text-[10px]! uppercase px-2 py-0.5 rounded">Mới</span>
                                                        @endif
                                                    </div>
                                                </h6>
                                            </div>
                                            <span class="menu-list-line"> </span>
                                            <div class="price"><span>{{ number_format($item->price) }}đ</span></div>
                                        </div>
                                        <div class="text desc">
                                            <a href="#">{{ $item->description ?? 'Đang cập nhật mô tả...' }}</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="dish-block">
                                    <div class="inner-box">
                                        <div class="text desc italic">Chưa có món ăn nào trong danh mục này.</div>
                                    </div>
                                </div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

</x-layouts.app>

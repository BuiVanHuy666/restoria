<x-layouts.app title="Đặt món online">
    <section class="inner-banner">
        <div class="image-layer" style="background-image: url({{ asset('images/background/banner-image-6.jpg') }});"></div>
        <div class="auto-container">
            <div class="inner">
                <h1>Đặt món online</h1>
                <div class="sub_text">
                    <p>
                        Trải nghiệm tinh hoa ẩm thực ngay tại nhà –
                        <span class="primary-color">mỗi món ăn là một tuyệt tác kết hợp giữa hương vị đỉnh cao và phong cách hiện đại.</span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="menu-section section-kt">
        <div class="auto-container">
            <div class="title-box centered">
                <div class="subtitle"><span>Tinh hoa ẩm thực</span></div>
                <h2>Thực Đơn Hấp Dẫn</h2>
            </div>

            <div class="tabs-box menu-tabs">

                <div class="buttons">
                    <ul class="tab-buttons clearfix">
                        @foreach($categories as $index => $category)
                            <li class="tab-btn {{ $index === 0 ? 'active-btn' : '' }}" data-tab="#tab-category-{{ $category->id }}">
                                <span>{{ $category->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="tabs-content">
                    @foreach($categories as $index => $category)
                        <div class="tab {{ $index === 0 ? 'active-tab' : '' }}" id="tab-category-{{ $category->id }}">
                            <div class="row clearfix">

                                @forelse($category->menuItems as $itemIndex => $item)
                                    <x-partials.menu-item-card :item="$item" :index="$itemIndex" />
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <p class="italic text-gray-500">Chưa có món ăn nào trong danh mục này.</p>
                                    </div>
                                @endforelse

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="open-timing">
                <div class="hours">Phục vụ hàng ngày từ <span class="theme_color">7:00 tối</span> đến
                    <span class="theme_color">9:00 tối</span></div>
                <div class="link-box">
                    <a href="{{ route('client.menu') }}" class="theme-btn btn-style-two clearfix">
                        <span class="btn-wrap">
                            <span class="text-one">xem toàn bộ thực đơn</span>
                            <span class="text-two">xem toàn bộ thực đơn</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

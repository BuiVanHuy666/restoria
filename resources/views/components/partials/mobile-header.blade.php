<section class="hidden-bar">
    <div class="inner-box">
        <div class="cross-icon hidden-bar-closer"><span class="far fa-close"></span></div>
        <div class="logo-box">
            <a href="{{ route('client.home') }}" title="Restoria"><img src="{{ asset('images/logo.png') }}" alt="image" title="Restoria"></a>
        </div>

        <div class="side-menu">
            <ul class="navigation clearfix">

                <li class="{{ request()->routeIs('client.about-us') ? 'current' : '' }}">
                    <a href="{{ route('client.about-us') }}">Giới thiệu</a>
                </li>
                <li class="{{ request()->routeIs('client.menu') ? 'current' : '' }}">
                    <a href="{{ route('client.menu') }}">Thực đơn nhà hàng</a>
                </li>
                <li class="{{ request()->routeIs('client.order-online') ? 'current' : '' }}">
                    <a href="{{ route('client.order-online') }}">Đặt món online</a>
                </li>
                <li class="{{ request()->routeIs('client.gallery') ? 'current' : '' }}">
                    <a href="{{ route('client.gallery') }}">Thư viện ảnh</a>
                </li>
                <li class="{{ request()->routeIs('client.contact') ? 'current' : '' }}">
                    <a href="{{ route('client.contact') }}">Liên hệ</a>
                </li>
                <li><a href="{{ route('client.book-table') }}">Đặt bàn</a></li>
            </ul>
        </div>

    </div>
</section>

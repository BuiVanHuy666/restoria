<header class="main-header header-down">
    <div class="header-top">
        <div class="auto-container">
            <div class="inner clearfix">
                <div class="top-left clearfix">
                    <ul class="top-info clearfix">
                        <li><i class="icon far fa-map-marker-alt"></i> 123 Lập trình, Công Nghệ, Thông Tin</li>
                        <li><i class="icon far fa-clock"></i> Hằng ngày : 8.00 am to 10.00 pm</li>
                    </ul>
                </div>
                <div class="top-right clearfix">
                    <ul class="top-info clearfix">
                        <li><a href="tel:+11234567890"><i class="icon far fa-phone"></i> +1 123 456 7890</a></li>
                        <li><a href="mailto:booking@restaurant.com"><i class="icon far fa-envelope"></i>booking@res.com</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header-upper">
        <div class="auto-container">
            <div class="main-box clearfix">
                <div class="logo-box">
                    <div class="logo">
                        <a href="{{ route('client.home') }}" title="Restoria Restaurant"><img src="{{ asset('images/logo.png') }}" alt="image" title="Restoria - Restaurants HTML Template"></a>
                    </div>
                </div>

                <div class="nav-box clearfix">
                    <div class="nav-outer clearfix">
                        <nav class="main-menu">
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
                                <li class="{{ request()->routeIs('client.book-table') ? 'current' : '' }}">
                                    <a href="{{ route('client.book-table') }}">Đặt bàn</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="links-box clearfix">
                        @guest
                            <div class="link link-btn">
                                <a href="{{ route('login') }}" class="theme-btn btn-style-two auth-btn-header clearfix">
                <span class="btn-wrap">
                    <span class="text-one">Đăng nhập</span>
                    <span class="text-two">Đăng nhập</span>
                </span>
                                </a>
                            </div>

                            <div class="link link-btn ml-2">
                                <a href="{{ route('register') }}" class="theme-btn btn-style-one auth-btn-header clearfix">
                <span class="btn-wrap">
                    <span class="text-one">Đăng ký</span>
                    <span class="text-two">Đăng ký</span>
                </span>
                                </a>
                            </div>
                        @endguest

                        @auth
                            <div class="link dropdown">
                                <a href="#" class="login-link dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon far fa-user-circle"></i> {{ auth()->user()->name }}
                                </a>
                                <div class="dropdown-menu auth-dropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Tài khoản</a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                                    </form>
                                </div>
                            </div>
                        @endauth

                        <div class="link info-toggler">
                            <button class="info-btn">
                                <span class="hamburger">
                                    <span class="top-bun"></span>
                                    <span class="meat"></span>
                                    <span class="bottom-bun"></span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="nav-toggler">
                        <button class="hidden-bar-opener">
                                <span class="hamburger">
                                    <span class="top-bun"></span>
                                    <span class="meat"></span>
                                    <span class="bottom-bun"></span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

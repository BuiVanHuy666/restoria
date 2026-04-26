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
                        <li><a href="mailto:booking@restaurant.com"><i class="icon far fa-envelope"></i>booking@res.com</a>
                        </li>
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
                                @foreach(config('menu.main') as $item)
                                    <li class="{{ request()->routeIs($item['route']) ? 'current' : '' }}">
                                        <a href="{{ route($item['route']) }}">{{$item['label']}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>

                    <div class="links-box clearfix">
                        @guest
                            <div class="link link-btn">
                                <a href="{{ route('login') }}" class="theme-btn btn-style-two auth-btn-header clearfix px-2">
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
                                    @if(auth()->user()->role === 'admin')
                                        <h6 class="dropdown-header text-primary">Quản trị hệ thống</h6>
                                        @foreach(config('menu.admin') as $item)
                                            <a class="dropdown-item" href="{{ route($item['route']) }}">
                                                <i class="fas fa-{{ $item['icon'] }} mr-2"></i> {{ $item['label'] }}
                                            </a>
                                        @endforeach
                                    @else
                                        <h6 class="dropdown-header">Tài khoản khách hàng</h6>
                                        @foreach(config('menu.customer') as $item)
                                            <a wire:navigate class="dropdown-item" href="{{ route($item['route']) }}">
                                                {{ $item['label'] }}
                                            </a>
                                        @endforeach
                                    @endif


                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="logout-btn dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                        </button>
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

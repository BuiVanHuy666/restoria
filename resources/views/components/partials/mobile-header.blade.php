<section class="hidden-bar">
    <div class="inner-box">
        <div class="cross-icon hidden-bar-closer"><span class="far fa-close"></span></div>
        <div class="logo-box">
            <a href="{{ route('client.home') }}" title="Restoria"><img src="{{ asset('images/logo.png') }}" alt="image" title="Restoria"></a>
        </div>

        <div class="side-menu">
            <ul class="navigation clearfix">
                @foreach(config('menu.main') as $item)
                    <li class="{{ request()->routeIs($item['route']) ? 'current' : '' }}">
                        <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
</section>

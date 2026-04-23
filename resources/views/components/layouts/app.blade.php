<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ isset($title) ? $title . ' - ' : '' }}Restoria | Hành trình tạo nên hương vị tinh tế</title>
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
<div class="page-wrapper">
{{--    <x-partials.preload/>--}}

    <x-partials.header/>

    <div class="menu-backdrop"></div>

    <x-partials.mobile-header/>

    <div class="info-back-drop"></div>

    <x-partials.offcanvas/>

    {{ $slot }}

    <x-partials.footer/>
    @include('sweetalert2::index')
</div>

<div class="scroll-to-top scroll-to-target" data-target="html"><span class="icon fa fa-angle-up"></span></div>

<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/jquery.fancybox.js') }}"></script>
<script src="{{ asset('js/swiper.js')}} "></script>
<script src="{{ asset('js/owl.js') }}"></script>
<script src="{{ asset('js/appear.js') }}" ></script>
<script src="{{ asset('js/wow.js') }}"></script>
<script src="{{ asset('js/parallax.min.js') }}"></script>
<script src="{{ asset('js/custom-script.js') }}"></script>

@stack('scripts')
</body>
</html>

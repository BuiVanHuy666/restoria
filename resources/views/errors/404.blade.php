<x-layouts.app title="Không tìm thấy trang | Restoria">
    <x-slot name="title">404 - Không tìm thấy trang | Restoria</x-slot>

    <section class="inner-banner">
        <div class="image-layer" style="background-image: url({{ asset('images/background/banner-bg.jpg') }});"></div>
        <div class="auto-container">
            <div class="inner">
                <div class="subtitle"><span>Rất tiếc!</span></div>
                <h1>404</h1>
                <div class="sub_text">
                    <p>Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển. <br>
                        Hãy thử quay lại trang chủ để tiếp tục khám phá thực đơn của chúng tôi.</p>
                </div>

                <div class="lower-link-box text-center mt-5">
                    <a href="{{ url('/') }}" class="theme-btn btn-style-two">
                        <span class="btn-wrap">
                            <span class="text-one">Quay lại trang chủ</span>
                            <span class="text-two">Quay lại trang chủ</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

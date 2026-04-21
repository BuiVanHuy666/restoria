<x-layouts.app title="Truy cập bị hạn chế | Restoria">
    <x-slot name="title">403 - Truy cập bị hạn chế | Restoria</x-slot>

    <section class="inner-banner">
        <div class="image-layer" style="background-image: url({{ asset('images/background/footer-bg.jpg') }});"></div>
        <div class="auto-container">
            <div class="inner">
                <div class="subtitle"><span style="color: #ff4d4d;">Quyền truy cập bị từ chối</span></div>
                <h1>403</h1>
                <div class="sub_text">
                    <p>Bạn không có quyền truy cập vào khu vực này. <br>
                        Nếu đây là một sự nhầm lẫn, vui lòng liên hệ với quản trị viên nhà hàng.</p>
                </div>

                <div class="lower-link-box text-center mt-5">
                    <a href="{{ url('/') }}" class="theme-btn btn-style-two">
                        <span class="btn-wrap">
                            <span class="text-one">Về trang chủ</span>
                            <span class="text-two">Về trang chủ</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

<x-layouts.auth
    title="Xác thực Email"
    bannerTitle="Xác Thực Tài Khoản"
    bannerDescription="Khởi đầu hành trình khám phá những hương vị tinh túy nhất tại Restoria."
    formSubtitle="Xác nhận thông tin"
    formTitle="Kiểm Tra Hộp Thư"
>
    <div class="text-white mb-4">
        Trân trọng cảm ơn Quý khách đã lựa chọn Restoria.
        <br><br>
        Để bảo mật thông tin và đảm bảo Quý khách không bỏ lỡ những đặc quyền dành riêng cho thành viên, vui lòng xác nhận địa chỉ email thông qua liên kết chúng tôi vừa gửi đến hộp thư của bạn.
        <br><br>
        Nếu vẫn chưa nhận được thư sau vài phút, Quý khách vui lòng nhấn vào nút bên dưới để chúng tôi gửi lại yêu cầu mới.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm" style="color: var(--main-color); font-style: italic;">
            * Một liên kết xác thực mới đã được gửi tới hòm thư của Quý khách thành công.
        </div>
    @endif

    <div class="row clearfix">
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <div class="field-inner">
                    <button type="submit" class="theme-btn btn-style-one clearfix w-100">
                        <span class="btn-wrap">
                            <span class="text-one">Gửi lại thư xác nhận</span>
                            <span class="text-two">Gửi lại thư xác nhận</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <div class="form-group col-lg-12 col-md-12 col-sm-12 text-center mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-white opacity-50 hover-opacity-100" style="text-decoration: underline; background: none; border: none; font-size: 13px; letter-spacing: 1px;">
                    Đăng xuất khỏi hệ thống
                </button>
            </form>
        </div>
    </div>
</x-layouts.auth>

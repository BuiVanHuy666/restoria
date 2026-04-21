<x-layouts.auth
    title="Đăng ký tài khoản"
    bannerTitle="Đăng Ký"
    bannerDescription="Tham gia cộng đồng Restoria để nhận những ưu đãi đặc quyền."
    formSubtitle="Tạo tài khoản mới"
    formTitle="Thông Tin Đăng Ký"
>
    <x-slot name="requestInfo">
        Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay tại đây</a>
    </x-slot>

    <form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
        @csrf
        <div class="row clearfix">
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Họ và Tên của bạn" required autofocus>
                    @error('name')
                    <span class="text-danger mt-1 d-block"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                <div class="field-inner">
                    <input type="text" name="email" value="{{ old('email') }}" placeholder="Địa chỉ Email" required>
                    @error('email')
                    <span class="text-danger mt-1 d-block"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                <div class="field-inner">
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Số điện thoại" required>
                    @error('phone')
                    <span class="text-danger mt-1 d-block"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                <div class="field-inner">
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                    @error('password')
                    <span class="text-danger mt-1 d-block"><small>{{ $message }}</small></span>
                    @enderror
                </div>
            </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                <div class="field-inner">
                    <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <button type="submit" class="theme-btn btn-style-one clearfix w-100">
                        <span class="btn-wrap">
                            <span class="text-one">đăng ký ngay</span>
                            <span class="text-two">đăng ký ngay</span>
                        </span>
                    </button>
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <div class="text-center text-white my-3"><small>— HOẶC —</small></div>
                    <a href="{{ url('auth/google') }}" class="theme-btn btn-style-two btn-google clearfix w-100">
                        <span class="btn-wrap">
                            <span class="text-one"><i class="fab fa-google mr-2"></i> Đăng ký bằng Google</span>
                            <span class="text-two"><i class="fab fa-google mr-2"></i> Đăng ký bằng Google</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.auth>

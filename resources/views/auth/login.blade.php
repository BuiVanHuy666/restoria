<x-layouts.auth
    title="Đăng nhập"
    bannerTitle="Đăng Nhập"
    bannerDescription="Chào mừng bạn quay trở lại với không gian ẩm thực Restoria."
    formSubtitle="Vui lòng đăng nhập"
    formTitle="Tài Khoản Của Bạn"
>
    <x-slot name="requestInfo">
        Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay tại đây</a>
    </x-slot>

    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf
        <div class="row clearfix">
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <input type="text" name="email" placeholder="Địa chỉ Email" value="{{ old('email') }}" autofocus>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <input type="password" name="password" id="password-field" placeholder="Mật khẩu">
                    <span class="toggle-password far fa-eye" id="toggle-password-icon"></span>

                    @error('password')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12 d-flex justify-content-between align-items-center mb-4">
                <label class="d-flex align-items-center text-white m-0" style="cursor: pointer;">
                    <input type="checkbox" name="remember" class="mr-2"> <small>Ghi nhớ đăng nhập</small>
                </label>
                <a href="{{ route('password.request') }}"><small class="primary-color">Quên mật khẩu?</small></a>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <button type="submit" class="theme-btn btn-style-one clearfix w-100">
                        <span class="btn-wrap">
                            <span class="text-one">đăng nhập</span>
                            <span class="text-two">đăng nhập</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.auth>

<x-layouts.auth
    title="Đặt lại mật khẩu"
    bannerTitle="Bảo Mật"
    bannerDescription="Vui lòng thiết lập mật khẩu mới an toàn cho tài khoản của Quý khách."
    formSubtitle="Bảo mật tài khoản"
    formTitle="Tạo Mật Khẩu Mới"
>
    <form method="POST" action="{{ route('reset-password.store') }}" id="reset-password-form">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="row clearfix">
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Địa chỉ Email" required readonly style="opacity: 0.7; cursor: not-allowed;">
                    @error('email')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <input type="password" name="password" id="password-field" placeholder="Mật khẩu mới" required autofocus>
                    <span class="toggle-password far fa-eye" id="toggle-password-icon"></span>

                    @error('password')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <input type="password" name="password_confirmation" id="password-confirm-field" placeholder="Xác nhận mật khẩu mới" required>
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12 mt-3">
                <div class="field-inner">
                    <button type="submit" class="theme-btn btn-style-one clearfix w-100">
                        <span class="btn-wrap">
                            <span class="text-one">Cập nhật mật khẩu</span>
                            <span class="text-two">Cập nhật mật khẩu</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.auth>

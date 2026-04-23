<x-layouts.auth
    title="Quên mật khẩu"
    bannerTitle="Quên Mật Khẩu"
    bannerDescription="Đừng lo lắng, hãy nhập email của bạn để bắt đầu quy trình khôi phục tài khoản."
    formSubtitle="Khôi phục truy cập"
    formTitle="Nhập Email Của Bạn"
>
    <x-slot name="requestInfo">
        Nhớ ra mật khẩu? <a href="{{ route('login') }}">Quay lại đăng nhập</a>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success mb-4" style="background: rgba(230,177,95,0.2); border: 1px solid var(--main-color); color: var(--main-color); padding: 15px; text-align: center;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password-reset.store') }}">
        @csrf
        <div class="row clearfix">
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <p class="text-white mb-3" style="font-size: 14px; opacity: 0.8">
                        Bạn quên mật khẩu? Đừng lo lắng! Hãy cho chúng tôi biết địa chỉ email của bạn. Chúng tôi sẽ gửi cho bạn một email để khôi phục lại.
                    </p>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Địa chỉ Email của bạn" required autofocus>

                    @error('email')
                    <span class="text-danger mt-2 d-block" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="field-inner">
                    <button type="submit" class="theme-btn btn-style-one clearfix w-100">
                        <span class="btn-wrap">
                            <span class="text-one">gửi liên kết khôi phục</span>
                            <span class="text-two">gửi liên kết khôi phục</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.auth>

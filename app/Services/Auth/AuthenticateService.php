<?php

namespace App\Services\Auth;

use App\Traits\HasRateLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class AuthenticateService
{
    use HasRateLimit;

    public function __invoke(Request $request): void
    {
        $throttleKey = Str::lower('login-attempt-'.$request->ip());
        if ($this->checkRateLimit($throttleKey)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            Alert::error('Tạm khóa', "Bạn đã thao tác quá nhiều lần. Vui lòng thử lại sau {$seconds} giây.");

            throw ValidationException::withMessages([
                'email' => "Bạn đã thao tác quá nhiều lần. Vui lòng thử lại sau {$seconds} giây."
            ]);
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            RateLimiter::clear($throttleKey);
        } else {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'Email hoặc mật khẩu không chính xác!'
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\HasRateLimit;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use SweetAlert2\Laravel\Swal;

class VerifyEmailController extends Controller
{
    use HasRateLimit;

    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect(route('customer.profile'));
        } else {
            return view('auth.verify-email');
        }
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('customer.profile', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('customer.profile', absolute: false).'?verified=1');
    }

    public function resend(Request $request)
    {
        $throttleKey = Str::lower('resend-verification-'.$request->ip());
        RateLimiter::hit($throttleKey);

        if ($this->checkRateLimit($throttleKey)) {
            Swal::warning([
                'title' => 'Tạm khóa',
                'text' => 'Bạn đã thao tác quá nhanh vui lòng thử lại sao'
            ]);
            return redirect()->back();
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended();
        }

        $request->user()->sendEmailVerificationNotification();

        return redirect()->back()->with('status', 'verification-link-sent');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function create()
    {
        return view('auth.password-reset');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'string', 'lowercase']
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && !empty($user->provider)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Tài khoản này được đăng ký qua ' . ucfirst($user->provider) . '. Vui lòng quay lại trang Đăng nhập và chọn "Đăng nhập bằng ' . ucfirst($user->provider) . '".'
                ]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}

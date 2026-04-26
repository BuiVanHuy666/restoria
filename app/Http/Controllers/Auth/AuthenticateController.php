<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthenticateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;

class AuthenticateController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request, AuthenticateService $authenticateService)
    {
        $authenticateService($request);

        Swal::success([
            'title' => 'Đăng nhập thành công',
            'text' => 'Rất vui được gặp lại Quý khách tại Restoria!',
            'confirmButtonText' => 'Bắt đầu đặt món'
        ]);

        return redirect(route('client.home'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        Swal::success([
            'title' => 'Đăng xuất thành công',
            'text' => 'Cảm ơn Quý khách đã sử dụng dịch vụ của Restoria. Hẹn sớm gặp lại Quý khách!',
            'timer' => 3000
        ]);

        return redirect(route('client.home'));
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthenticateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AuthenticateController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request, AuthenticateService $authenticateService)
    {
        $authenticateService($request);

        Alert::success('Đăng nhập thành công');
        return redirect(route('client.home'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        Alert::success('Đăng xuất thành công');

        return redirect(route('client.home'));
    }
}

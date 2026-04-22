<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\RegisterService;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class RegisterUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request, RegisterService $registerService)
    {
        try {
            $registerService($request->validated());
            Alert::success('Đăng ký thành công');

            return redirect(route('client.home'));
        } catch (\Exception $exception) {
            Log::channel('authentication')->error("Lỗi đăng ký: " . $exception->getMessage());
            return back()->withInput()->withErrors(['name' => 'Hệ thống đang bận, vui lòng thử lại sau!']);
        }
    }
}

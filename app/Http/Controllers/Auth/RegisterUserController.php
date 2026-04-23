<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\RegisterService;
use Illuminate\Support\Facades\Log;
use SweetAlert2\Laravel\Swal;

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
            Swal::success([
                'title' => 'Khởi tạo tài khoản thành công',
                'text' => 'Chào mừng Quý khách đến với Restoria. Chúc Quý khách có những trải nghiệm ẩm thực tuyệt vời!',
                'confirmButtonText' => 'Khám phá ngay'
            ]);


            return redirect(route('client.home'));
        } catch (\Exception $exception) {
            Log::channel('authentication')->error("Lỗi đăng ký: " . $exception->getMessage());
            return back()->withInput()->withErrors(['name' => 'Hệ thống đang bận, vui lòng thử lại sau!']);
        }
    }
}

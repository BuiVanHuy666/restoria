<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\SocialUserService;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Socialite;
use SweetAlert2\Laravel\Swal;

class SocialAuthController extends Controller
{
    const array allowedProviders = ['google'];

    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, self::allowedProviders), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider, SocialUserService $socialUserService)
    {
        abort_unless(in_array($provider, self::allowedProviders), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();

            $socialUserService->findOrCreateUser($provider, $socialUser);

            Swal::success([
                'title' => 'Kết nối thành công',
                'text' => 'Hệ thống đã nhận diện tài khoản của Quý khách.',
                'confirmButtonText' => 'Tiếp tục'
            ]);
            return redirect()->route('client.home');
        } catch (\Exception $e) {
            Log::driver('authentication')->error("Lỗi đăng nhập Socialite ({$provider}): ".$e->getMessage());

            Swal::error([
                'title' => 'Kết nối gián đoạn',
                'text' => 'Quá trình đăng nhập qua mạng xã hội gặp sự cố. Quý khách vui lòng kiểm tra lại kết nối.',
            ]);
            return redirect()->route('login');
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\SocialUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Socialite;
use phpDocumentor\Reflection\Types\Self_;
use RealRashid\SweetAlert\Facades\Alert;

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

            Alert::success('Đăng nhập thành công!');
            return redirect()->route('client.home');
        } catch (\Exception $e) {
            Log::driver('authentication')->error("Lỗi đăng nhập Socialite ({$provider}): ".$e->getMessage());

            Alert::error('Lỗi', 'Quá trình đăng nhập bị gián đoạn. Vui lòng thử lại.');
            return redirect()->route('login');
        }
    }
}

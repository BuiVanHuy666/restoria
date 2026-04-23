<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialUserService
{
    public function findOrCreateUser(string $provider, SocialiteUser $socialUser): User
    {
        $user = User::updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name'              => $socialUser->getName() ?? $socialUser->getNickname(),
                'provider'          => $provider,
                'provider_id'       => $socialUser->getId(),
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        Auth::login($user);

        return $user;
    }
}

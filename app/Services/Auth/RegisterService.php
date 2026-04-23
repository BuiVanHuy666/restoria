<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterService
{
    public function __invoke(array $validatedData): User
    {
        $user = User::create($validatedData);

        event(new Registered($user));

        Auth::login($user);
        return $user;
    }
}

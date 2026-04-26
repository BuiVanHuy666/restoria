<?php

namespace App\Services\Auth;

use App\Models\User;

class UpdatePasswordService {
    public function __invoke(User $user, array $validatedData): bool
    {
        return $user->update([
            'password' => $validatedData['password'],
        ]);
    }
}

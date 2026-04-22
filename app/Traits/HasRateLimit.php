<?php

namespace App\Traits;

use Illuminate\Support\Facades\RateLimiter;
use RealRashid\SweetAlert\Facades\Alert;

trait HasRateLimit
{
    protected function checkRateLimit(string $throttleKey, int $maxAttempts = 5): bool
    {
        return RateLimiter::tooManyAttempts($throttleKey, $maxAttempts);
    }
}

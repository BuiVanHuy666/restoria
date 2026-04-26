<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'is_active',
    'phone_number',
    'provider',
    'provider_id',
    'email_verified_at'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($sq) use ($search) {
                $sq->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%")
                   ->orWhere('phone_number', 'like', "%{$search}%");
            });
        });

        $query->when($filters['role'] ?? null, fn($q, $role) => $q->where('role', $role));

        $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('is_active', $status === 'active');
        });
    }
}

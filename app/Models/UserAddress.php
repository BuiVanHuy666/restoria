<?php

namespace App\Models;

use App\Services\Core\LocationService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['receiver_name', 'receiver_phone_number', 'province_code', 'ward_code', 'address_detail', 'is_default'])]
class UserAddress extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function provinceName(): Attribute
    {
        return Attribute::make(
            get: fn () => app(LocationService::class)->getProvinceName($this->province_code),
        );
    }

    protected function wardName(): Attribute
    {
        return Attribute::make(
            get: fn () => app(LocationService::class)->getWardName($this->province_code, $this->ward_code),
        );
    }

    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->address_detail}, {$this->ward_name}, {$this->province_name}",
        );
    }
}

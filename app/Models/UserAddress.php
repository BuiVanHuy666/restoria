<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['receiver_name', 'receiver_phone_numer', 'province_code', 'ward_code', 'address_detail', 'is_default'])]
class UserAddress extends Model
{
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}

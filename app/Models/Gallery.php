<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded([])]
class Gallery extends Model
{
    public const string GALLERIES_PATH = 'galleries/';

    public const array GALLERY_TYPES = [
        'space' => 'Không gian nhà hàng',
        'food' => 'Thức ăn & Thức uống',
        'guest' => 'Thực khách'
    ];
}

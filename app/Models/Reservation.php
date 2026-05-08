<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => ReservationStatus::class,
        ];
    }
}

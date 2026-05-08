<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Đang chờ',
            self::CONFIRMED => 'Đã xác nhận',
            self::COMPLETED => 'Hoàn thành',
            self::CANCELLED => 'Đã hủy',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'orange',
            self::CONFIRMED => 'blue',
            self::COMPLETED => 'emerald',
            self::CANCELLED => 'rose',
        };
    }
}

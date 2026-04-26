<?php

namespace App\Enums;

enum MenuItemStatus: string
{
    case AVAILABLE = 'available';
    case SOLD_OUT = 'sold_out';
    case HIDDEN = 'hidden';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Còn hàng',
            self::SOLD_OUT => 'Hết hàng',
            self::HIDDEN => 'Đang Ẩn',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AVAILABLE => 'emerald',
            self::SOLD_OUT => 'rose',
            self::HIDDEN => 'zinc',
        };
    }
}

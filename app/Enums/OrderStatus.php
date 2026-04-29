<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PREPARING = 'preparing';
    case SHIPPING = 'shipping';
    case DELIVERED = 'delivered';
    case CANCELED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ xác nhận',
            self::CONFIRMED => 'Đã xác nhận',
            self::PREPARING => 'Đang chuẩn bị',
            self::SHIPPING => 'Đang vận chuyển',
            self::DELIVERED => 'Giao hàng thành công',
            self::CANCELED => 'Đã hủy',
        };
    }

    // ==========================================
    // DÀNH CHO ADMIN (FLUX UI + TAILWIND)
    // ==========================================
    public function adminColor(): string
    {
        return match ($this) {
            self::PENDING => 'amber',
            self::CONFIRMED => 'blue',
            self::PREPARING => 'indigo',
            self::SHIPPING => 'sky',
            self::DELIVERED => 'emerald',
            self::CANCELED => 'rose', // Flux/Tailwind thường dùng 'rose' hoặc 'red' thay vì 'danger'
        };
    }

    public function adminIcon(): string
    {
        return match ($this) {
            self::PENDING => 'clock',
            self::CONFIRMED => 'check',
            self::PREPARING => 'arrow-path',
            self::SHIPPING => 'truck',
            self::DELIVERED => 'check-circle',
            self::CANCELED => 'x-circle',
        };
    }

    // ==========================================
    // DÀNH CHO CLIENT (BOOTSTRAP + FONT AWESOME)
    // ==========================================
    public function clientClass(): string
    {
        // Trả về class màu của Bootstrap (text-warning, text-success, v.v.)
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED, self::SHIPPING => 'primary',
            self::PREPARING => 'info',
            self::DELIVERED => 'success',
            self::CANCELED => 'danger',
        };
    }

    public function clientIcon(): string
    {
        return match ($this) {
            self::PENDING => 'clock',
            self::CONFIRMED => 'check-circle',
            self::PREPARING => 'fire-burner',
            self::SHIPPING => 'truck-fast',
            self::DELIVERED => 'box-open',
            self::CANCELED => 'times-circle',
        };
    }
}

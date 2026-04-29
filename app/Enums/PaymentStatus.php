<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PAID = 'paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID   => 'Chưa thanh toán',
            self::PAID     => 'Đã thanh toán',
            self::FAILED   => 'Thất bại',
            self::REFUNDED => 'Đã hoàn tiền',
        };
    }

    // ==========================================
    // DÀNH CHO ADMIN (FLUX UI + TAILWIND)
    // ==========================================
    public function adminColor(): string
    {
        return match ($this) {
            self::UNPAID   => 'zinc',
            self::PAID     => 'emerald',
            self::FAILED   => 'rose',
            self::REFUNDED => 'sky'
        };
    }

    public function adminIcon(): string
    {
        return match ($this) {
            self::UNPAID   => 'clock',
            self::PAID     => 'check-circle',
            self::FAILED   => 'x-circle',
            self::REFUNDED => 'arrow-path',
        };
    }

    // ==========================================
    // DÀNH CHO CLIENT (BOOTSTRAP + FONT AWESOME)
    // ==========================================
    public function clientClass(): string
    {
        return match ($this) {
            self::UNPAID   => 'secondary',
            self::PAID     => 'success',
            self::FAILED   => 'danger',
            self::REFUNDED => 'info',
        };
    }

    public function clientIcon(): string
    {
        return match ($this) {
            self::UNPAID   => 'clock',
            self::PAID     => 'check-circle',
            self::FAILED   => 'times-circle',
            self::REFUNDED => 'undo-alt',
        };
    }
}

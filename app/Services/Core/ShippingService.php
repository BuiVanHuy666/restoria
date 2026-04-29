<?php
namespace App\Services\Core;

class ShippingService
{
    public function calculateFee($subTotal): int
    {
        if ($subTotal >= 2000000) {
            return 0;
        }

        if ($subTotal >= 1000000) {
            return 30000;
        }

        return 50000;
    }
}

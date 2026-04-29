<?php

namespace App\Services\Core;

use App\Models\MenuItem;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Collection;

class PromotionService
{
    protected static $activePromotions = null;

    public function getActivePromotions(): ?Collection
    {
        if (self::$activePromotions === null) {
            $now = now();

            self::$activePromotions = Promotion::with(['categories', 'menuItems'])
                                               ->where('is_active', true)
                                               ->where('starts_at', '<=', $now)
                                               ->where('ends_at', '>=', $now)
                                               ->get();
        }

        return self::$activePromotions;
    }

    public function calculateBestPrice(MenuItem $item): float
    {
        $originalPrice = $item->price;
        $promotions = $this->getActivePromotions();

        if ($promotions->isEmpty()) return $originalPrice;

        $applicablePromotions = $promotions->filter(function ($promo) use ($item) {
            if ($promo->apply_to === 'all') return true;
            if ($promo->apply_to === 'items') return $promo->menuItems->contains('id', $item->id);
            if ($promo->apply_to === 'categories') {
                return $promo->categories->pluck('id')->intersect($item->categories->pluck('id'))->isNotEmpty();
            }
            return false;
        });

        if ($applicablePromotions->isEmpty()) return $originalPrice;

        $minPrice = $originalPrice;
        foreach ($applicablePromotions as $promo) {
            $discount = $promo->discount_type === 'percentage'
                ? min($originalPrice * ($promo->discount_value / 100), $promo->max_discount_amount ?? INF)
                : $promo->discount_value;
            $minPrice = min($minPrice, max(0, $originalPrice - $discount));
        }

        return $minPrice;
    }
}

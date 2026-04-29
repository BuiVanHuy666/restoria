<?php

namespace App\Models;

use App\Enums\MenuItemStatus;
use App\Services\Core\PromotionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded([])]
class MenuItem extends Model
{
    use SoftDeletes;
    public const string IMAGE_PATH = 'menu-items/';

    protected $casts = [
        'status' => MenuItemStatus::class,
        'is_new' => 'boolean',
        'is_popular' => 'boolean',
        'is_round_image' => 'boolean',
        'is_online_sale' => 'boolean'
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_menu_item');
    }

    public function discountedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => app(PromotionService::class)->calculateBestPrice($this)
        );
    }

    public function isAvailable(): bool
    {
        return $this->status === MenuItemStatus::AVAILABLE;
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image ?
                Storage::url(self::IMAGE_PATH . $this->image) :
                asset('images/resource/default_food.jpg')
        );
    }

    public function hasDiscount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->discounted_price < $this->price
        );
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['category_id'] ?? null, function ($query, $categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });
    }

    public function scopeAvailable(Builder $query)
    {
        return $query->where('status', MenuItemStatus::AVAILABLE->value);
    }

    public function scopeNew(Builder $query) {
        return $query->where('is_new', true);
    }

    public function scopePopular(Builder $query) {
        return $query->where('is_popular', true);
    }

    public function scopeOnlineSale(Builder $query): void
    {
        $query->where('allow_online_sale', true)
              ->whereHas('categories', fn($q) => $q->where('allow_online_sale', true));
    }


}

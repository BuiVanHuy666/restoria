<?php

namespace App\Models;

use App\Enums\MenuItemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded([])]
class MenuItem extends Model
{
    public const string IMAGE_PATH = 'menu-items/';

    protected $casts = [
        'status' => MenuItemStatus::class,
        'is_new' => 'boolean',
        'is_popular' => 'boolean',
        'is_round_image' => 'boolean',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_menu_item');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image ?
                Storage::url(self::IMAGE_PATH . $this->image) :
                asset('images/resource/default_food.jpg')
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
}

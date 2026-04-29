<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'slug', 'description', 'sort_order', 'thumbnail', 'allow_online_sale'])]
class Category extends Model
{
    public const string THUMBNAIL_PATH = 'categories/';

    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'category_menu_item');
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->thumbnail ? Storage::url(self::THUMBNAIL_PATH . $this->thumbnail) : asset(
                'images/default-category.png'
            )
        );
    }

    public function scopeOnlineSale(Builder $query)
    {
        return $query->where('allow_online_sale', true);
    }
}

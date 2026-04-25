<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'slug', 'description', 'sort_order', 'thumbnail'])]
class Category extends Model
{
    public const string THUMBNAIL_PATH = 'categories/';

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->thumbnail ? Storage::url(self::THUMBNAIL_PATH . $this->thumbnail) : asset(
                'images/default-category.png'
            )
        );
    }
}

<?php

namespace App\Models;

use App\Enums\MenuItemStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable('code', 'name', 'slug', 'description', 'price', 'image', 'status')]
class MenuItem extends Model
{
    protected $casts = [
        'status' => MenuItemStatus::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}

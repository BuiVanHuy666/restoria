<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'sub_total',
        'shipping_fee',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'code',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'shipping_ward',
        'shipping_province',
        'subtotal',
        'discount',
        'payment_detail',
        'note',
        'paid_at'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'payment_detail' => 'json'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => OrderStatus::tryFrom($this->status)?->label() ?? 'Không rõ'
        );
    }
}

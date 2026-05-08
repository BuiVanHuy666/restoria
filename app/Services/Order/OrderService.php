<?php
namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function create($cartItems, $subTotal, $shippingFee, $addressId, $orderNote = null)
    {
        return DB::transaction(function () use ($cartItems, $subTotal, $shippingFee, $addressId, $orderNote) {
            $address = UserAddress::findOrFail($addressId);

            $orderCode = 'ORD-' . date('ymd') . '-' . strtoupper(Str::random(5));

            $order = Order::create([
                'code' => $orderCode,
                'user_id' => auth()->id(),

                'customer_name' => $address->receiver_name,
                'customer_phone' => $address->receiver_phone_number,
                'shipping_address' => $address->address_detail,
                'shipping_ward' => $address->ward_name,
                'shipping_province' => $address->province_name,

                'subtotal' => $subTotal,
                'shipping_fee' => $shippingFee,
                'discount' => 0,
                'total_amount' => $subTotal + $shippingFee,
                'note' => $orderNote,

                'status' => OrderStatus::PENDING,
                'payment_method' => 'vnpay',
                'payment_status' => PaymentStatus::UNPAID,
            ]);

            foreach ($cartItems as $item) {
                $menuItem = $item->menuItem;
                $originalPrice = $menuItem->price;
                $itemPrice = $originalPrice;
                $discountAmount = 0;
                $promotionId = null;

                $promotion = $menuItem->activePromotion;

                if ($promotion) {
                    $promotionId = $promotion->id;

                    if ($promotion->discount_type === 'percentage') {
                        $discountAmount = ($originalPrice * $promotion->discount_value) / 100;
                        if ($promotion->max_discount_amount && $discountAmount > $promotion->max_discount_amount) {
                            $discountAmount = $promotion->max_discount_amount;
                        }
                    } elseif ($promotion->discount_type === 'fixed') {
                        $discountAmount = $promotion->discount_value;
                    }

                    $itemPrice = max($originalPrice - $discountAmount, 0);
                }

                $order->items()->create([
                    'menu_item_id' => $item->menu_item_id,
                    'original_price' => $originalPrice,
                    'discount_amount' => $discountAmount,
                    'item_price' => $itemPrice,
                    'quantity' => $item->quantity,
                    'note' => $item->note,
                    'promotion_id' => $promotionId
                ]);
            }

            $totalDiscount = $order->items->sum(function($orderItem) {
                return $orderItem->discount_amount * $orderItem->quantity;
            });

            $order->update(['discount' => $totalDiscount]);

            return $order;
        });
    }
}

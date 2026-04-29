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
                $order->items()->create([
                    'menu_item_id' => $item->menu_item_id,
                    'original_price' => $item->menuItem->price,
                    'discount_amount' => 0,
                    'item_price' => $item->menuItem->price,
                    'quantity' => $item->quantity,
                    'note' => $item->note,
                ]);
            }
            return $order;
        });
    }
}

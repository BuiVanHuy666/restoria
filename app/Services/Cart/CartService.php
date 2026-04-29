<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartService
{
    protected function getOrCreateCart()
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function getCartItems()
    {
        return $this->getOrCreateCart()->items()->with('menuItem')->get();
    }

    public function add(int $productId, int $quantity = 1)
    {
        $cart = $this->getOrCreateCart();

        $cartItem = $cart->items()->where('menu_item_id', $productId)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'menu_item_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }

    public function remove(int $cartItemId): void
    {
        CartItem::where('id', $cartItemId)->delete();
    }

    public function getTotal()
    {
        $items = $this->getCartItems();
        return $items->sum(function ($item) {
            return $item->menuItem->price * $item->quantity;
        });
    }

    public function getCount() {
        if (!Auth::check()) return 0;
        return $this->getOrCreateCart()->items()->sum('quantity');
    }

    public function updateNote(int $cartItemId, ?string $note): void
    {
        CartItem::where('id', $cartItemId)->update(['note' => $note]);
    }

    public function clear(): void
    {
        Cart::where('user_id', auth()->user()->id)->delete();
    }
}

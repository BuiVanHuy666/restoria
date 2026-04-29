<?php

use App\Services\Cart\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public int $count = 0;

    public function mount(): void
    {
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount(): void
    {
        $this->count = app(CartService::class)->getCount();
    }
};
?>

<span class="cart-badge"
      style="
            position: absolute;
            top: -8px;
            right: -10px;
            background-color: #ff3131;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            line-height: 1;"
>{{ $count }}</span>

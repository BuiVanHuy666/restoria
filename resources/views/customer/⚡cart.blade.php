<?php

use App\Models\CartItem;
use App\Services\Cart\CartService;
use App\Services\Core\ShippingService;
use App\Services\Order\OrderService;
use App\Services\Payment\VNPayService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use SweetAlert2\Laravel\Traits\WithSweetAlert;

new #[Layout('components.layouts.app', ['title' => 'Giỏ hàng'])]
class extends Component {
    use WithSweetAlert;

    protected CartService $cartService;
    protected ShippingService $shippingService;
    public $selectedAddressId;

    public string $orderNote = '';

    public function boot(CartService $cartService, ShippingService $shippingService): void
    {
        $this->cartService = $cartService;
        $this->shippingService = $shippingService;

        if (auth()->check() && $this->addresses->isNotEmpty()) {
            $this->selectedAddressId = $this->addresses->where('is_default', true)->first()->id
                ?? $this->addresses->first()->id;
        }
    }

    #[Computed]
    public function cartItems()
    {
        return $this->cartService->getCartItems();
    }

    #[Computed]
    public function totalAmount()
    {
        return $this->cartService->getTotal();
    }

    #[Computed]
    public function shippingFee()
    {
        return $this->shippingService->calculateFee($this->totalAmount);
    }

    #[Computed]
    public function addresses()
    {
        return auth()->check() ? auth()->user()->addresses : collect();
    }

    public function increment($productId): void
    {
        $this->cartService->add($productId, 1);
        $this->dispatch('cart-updated');
    }

    public function decrement($cartItemId, $productId): void
    {
        $item = CartItem::find($cartItemId);

        if ($item && $item->quantity > 1) {
            $this->cartService->add($productId, -1);
        } else {
            $this->removeItem($cartItemId);
        }
        $this->dispatch('cart-updated');
    }

    public function removeItem($cartItemId): void
    {
        $this->cartService->remove($cartItemId);
        $this->dispatch('cart-updated');
    }

    public function updateItemNote($cartItemId, $note): void
    {
        $this->cartService->updateNote($cartItemId, $note);
    }

    public function checkout(VNPayService $vnpayService, OrderService $orderService)
    {
        if ($this->cartItems->isEmpty()) {
            return;
        }

        if (!$this->selectedAddressId) {
            $this->swalError([
                'title' => 'Lỗi',
                'text' => 'Vui lòng chọn địa chỉ giao hàng để tiến hành thanh toán.'
            ]);
            return;
        }

        try {

            $order = $orderService->create(
                $this->cartItems,
                $this->totalAmount,
                $this->shippingFee,
                $this->selectedAddressId,
                $this->orderNote
            );

            $orderId = $order->code;
            $total = $order->total_amount;
            $orderInfo = "Thanh toan don hang ".$orderId;

            $paymentUrl = $vnpayService->createPaymentUrl($orderId, $total, $orderInfo);

            return $this->redirect($paymentUrl);
        } catch (\Exception $e) {
            $this->swalError([
                'title' => 'Lỗi hệ thống',
                'text' => 'Đã có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại sau!' . $e->getMessage()
            ]);
            return;
        }
    }
};
?>

<div>
    <x-partials.inner-banner title="Giỏ Hàng" :image="asset('images/background/banner-image-1.jpg')">
        <p>Kiểm tra lại lựa chọn của bạn -
            <span class="primary-color">chuẩn bị cho một trải nghiệm ẩm thực tinh tế.</span></p>
    </x-partials.inner-banner>

    <section class="cart-page section-kt">
        <div class="auto-container">
            <div class="row clearfix">

                <div class="column col-lg-8 col-md-12 col-sm-12">
                    <div class="cart-outer-container">
                        <table class="cart-table-premium">
                            <thead>
                            <tr>
                                <th class="prod-column">Món ăn</th>
                                <th class="price-column">Đơn giá</th>
                                <th class="qty-column">Số lượng</th>
                                <th class="sub-total">Thành tiền</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($this->cartItems as $item)
                                <tr wire:key="cart-item-{{ $item->id }}">
                                    <td class="prod-column">
                                        <div class="column-box">
                                            <figure class="prod-thumb">
                                                <img src="{{ $item->menuItem->thumbnail_url }}" alt="{{ $item->menuItem->name }}">
                                            </figure>
                                            <h6 class="mt-2">{{ $item->menuItem->name }}</h6>
                                            <input type="text"
                                                   placeholder="Ghi chú (VD: ít cay, không hành...)"
                                                   class="form-control form-control-sm bg-transparent text-white border-secondary"
                                                   style="font-size: 12px; padding: 4px 8px; max-width: 200px;"
                                                   value="{{ $item->note }}"
                                                   wire:change="updateItemNote({{ $item->id }}, $event.target.value)">
                                        </div>
                                    </td>
                                    <td class="price-column">{{ number_format($item->menuItem->price) }}đ</td>
                                    <td class="qty-column">
                                        <div class="quantity-spinner-premium">
                                            <button type="button" wire:click="decrement({{ $item->id }}, {{ $item->menu_item_id }})" class="minus-btn">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="text" value="{{ $item->quantity }}" readonly class="qty-input">
                                            <button type="button" wire:click="increment({{ $item->menu_item_id }})" class="plus-btn">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="sub-total">
                                        {{ number_format($item->menuItem->price * $item->quantity) }}đ
                                    </td>
                                    <td class="remove-column">
                                        <button wire:click="removeItem({{ $item->id }})" class="remove-btn"
                                                wire:confirm="Bạn có chắc muốn xóa món này khỏi giỏ hàng?">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 italic text-gray-400">
                                        Giỏ hàng trống.
                                        <a href="{{ route('client.order-online') }}" class="text-primary underline">Tiếp tục chọn món.</a>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="column col-lg-4 col-md-12 col-sm-12">
                    <div class="cart-summary-premium">
                        <div class="summary-inner">
                            <h4 class="summary-title">Tóm tắt đơn hàng</h4>
                            <ul class="summary-list">
                                @php $total = $this->totalAmount; @endphp
                                <li class="clearfix">Tổng tiền món: <span>{{ number_format($total) }}đ</span></li>
                                <li class="clearfix">Phí giao hàng: <span>{{ number_format($this->shippingFee) }}</span>
                                </li>
                                <li class="total clearfix">Tổng thanh toán:
                                    <span class="total-price">{{ number_format($total + $this->shippingFee) }}đ</span>
                                </li>
                            </ul>

                            <div class="order-note-box mt-4 mb-3">
                                <label class="text-white mb-2" style="font-size: 14px;"><i class="far fa-edit mr-2"></i>Ghi chú đơn hàng</label>
                                <textarea wire:model="orderNote"
                                          class="form-control bg-transparent text-white border-secondary rounded"
                                          rows="3"
                                          style="resize: none;"
                                          placeholder="VD: Giao trong giờ hành chính, gọi trước khi đến..."></textarea>
                            </div>

                            <div class="checkout-btn-box mt-4">
                                @if($this->cartItems->count() > 0)
                                    <button wire:click="checkout" class="theme-btn btn-style-one w-full text-center py-3 border-0">
                                        <span class="btn-wrap">
                                            <span class="text-one">Tiến hành thanh toán</span>
                                            <span class="text-two">Tiến hành thanh toán</span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="delivery-address-container mt-5">
                        <h4 class="mb-4">Thông tin giao hàng</h4>

                        @if($this->addresses->isEmpty())
                            <div class="p-4 text-center rounded">
                                <p class="italic text-muted mb-2">Bạn chưa có địa chỉ nhận hàng nào được lưu.</p>
                                <a href="{{ route('customer.address') }}" class="text-primary" style="text-decoration: underline;">
                                    + Thêm địa chỉ mới ngay
                                </a>
                            </div>
                        @else
                            <div class="address-list">
                                @foreach($this->addresses as $address)
                                    <label class="address-item p-3 border rounded mb-3 d-flex align-items-center"
                                           style="cursor: pointer; {{ $selectedAddressId == $address->id ? 'border-color: #c9ab81' : '' }}">

                                        <input type="radio" wire:model.live="selectedAddressId" value="{{ $address->id }}" class="mr-3" style="width: 18px; height: 18px; accent-color: #c9ab81;">

                                        <div class="ml-3" style="flex: 1;">
                                            <h6 class="mb-1">
                                                {{ $address->receiver_name }}
                                                <span class="text-muted" style="font-weight: normal;">- {{ $address->receiver_phone_number }}</span>
                                                @if($address->is_default)
                                                    <span class="badge" style="background-color: #c9ab81; color: #fff; font-size: 10px; margin-left: 8px;">Mặc định</span>
                                                @endif
                                            </h6>
                                            <p class="mb-0 text-muted" style="font-size: 14px;">
                                                {{ $address->full_address }}
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('selectedAddressId')
                            <span class="text-danger mt-2 d-block" style="font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </span>
                            @enderror
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

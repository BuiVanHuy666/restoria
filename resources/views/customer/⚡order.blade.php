<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Str;

new #[Layout('components.layouts.dashboard', [
    'title' => 'Đơn hàng',
    'header' => 'Lịch Sử Thưởng Thức',
    'description' => 'Xem lại danh sách những món ăn tinh túy Quý khách đã đặt tại Restoria.'
])]
class extends Component {

    public ?Order $selectedOrder = null;

    #[Computed]
    public function orders()
    {
        return auth()
            ->user()->orders()
            ->with('items.menuItem')
            ->latest()
            ->get();
    }

    public function viewOrder($id): void
    {
        $this->selectedOrder = Order::with('items.menuItem')->find($id);
    }
};
?>

<div>
    @forelse($this->orders as $order)
        <div class="order-card-premium mb-4" wire:key="order-{{ $order->id }}">
            <div class="order-header">
                <div class="order-id">#{{ $order->code }}</div>
                <div class="order-date">Ngày đặt: {{ $order->created_at->format('d/m/Y - H:i') }}</div>
            </div>

            <div class="order-body">
                <div class="order-dishes">
                    <i class="fas fa-utensils mr-2" style="color: var(--main-color, #c9ab81);"></i>
                    {{ Str::limit($order->items->pluck('menuItem.name')->implode(', '), 80, '...') }}
                </div>

                <div class="order-price-group">
                    <span class="order-total-label">Tổng thanh toán</span>
                    <span class="order-total-value">{{ number_format($order->total_amount) }}đ</span>
                </div>
            </div>

            <div class="order-footer mt-4 d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center" style="gap: 15px;">
                    <span class="status-pill text-{{ $order->status->clientClass() }} font-weight-bold">
                        <i class="fas fa-{{ $order->status->clientIcon() }} mr-1"></i>
                        {{ $order->status->label() }}
                    </span>

                    <span class="text-muted" style="font-size: 10px;">|</span>

                    <span class="status-pill text-{{ $order->payment_status->clientClass() }} font-weight-bold">
                        <i class="fas fa-{{ $order->payment_status->clientIcon() }} mr-1"></i>
                        {{ $order->payment_status->label() }}

                        @if($order->payment_method == 'vnpay' && $order->payment_status->value == 'paid')
                            <i class="fas fa-check-circle ml-1 text-success" title="Đã thanh toán"></i>
                        @endif
                    </span>
                </div>

                <button type="button"
                        wire:click="viewOrder({{ $order->id }})"
                        data-toggle="restoria-modal"
                        data-target="#orderDetailModal"
                        class="theme-btn btn-style-one btn-sm border-0"
                        style="padding: 8px 25px;">
                    <span class="btn-wrap">
                        <span class="text-one">Chi tiết đơn</span>
                        <span class="text-two">Chi tiết đơn</span>
                    </span>
                </button>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="glass-widget-box" style="opacity: 0.7;">
                <i class="fas fa-receipt fa-3x mb-3 text-secondary"></i>
                <p class="text-white">Bạn chưa có đơn hàng nào.</p>
                <a href="{{ route('client.order-online') }}" class="theme-btn btn-style-one btn-sm mt-3 border-0">
                    <span class="btn-wrap">
                        <span class="text-one">Đặt món ngay</span>
                        <span class="text-two">Đặt món ngay</span>
                    </span>
                </a>
            </div>
        </div>
    @endforelse

    <template x-teleport="body">
        <div class="restoria-modal" id="orderDetailModal" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" style="background-color: #1a1a1a; color: #fff; border: 1px solid #c9ab81;">

                    <div class="modal-header" style="border-bottom: 1px solid #333;">
                        <h5 class="modal-title" style="color: #c9ab81;">
                            Chi tiết đơn hàng @if($selectedOrder) #{{ $selectedOrder->code }} @endif
                        </h5>
                        <button type="button" class="close text-white restoria-modal-close" aria-label="Close" style="outline: none; background: none; border: none; font-size: 1.5rem;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4">
                        @if($selectedOrder)

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="p-3 rounded h-100" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid #333;">
                                        <span class="d-block text-muted mb-1" style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Trạng thái đơn hàng</span>
                                        <span class="font-weight-bold text-{{ $selectedOrder->status->clientClass() }}" style="font-size: 16px;">
                                            <i class="fas fa-{{ $selectedOrder->status->clientIcon() }} mr-2"></i>{{ $selectedOrder->status->label() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded h-100" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid #333;">
                                        <span class="d-block text-muted mb-1" style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Thanh toán</span>
                                        <span class="font-weight-bold text-{{ $selectedOrder->payment_status->clientClass() }}" style="font-size: 16px;">
                                            <i class="fas fa-{{ $selectedOrder->payment_status->clientIcon() }} mr-2"></i>{{ $selectedOrder->payment_status->label() }}

                                            @if($selectedOrder->payment_method == 'vnpay')
                                                <small class="text-muted ml-1" style="font-size: 11px;">(VNPay)</small>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="delivery-info-box p-3 mb-4 rounded" style="background-color: rgba(201, 171, 129, 0.1); border-left: 3px solid #c9ab81;">
                                <h6 class="text-uppercase mb-3" style="color: #c9ab81; font-size: 14px;">
                                    <i class="fas fa-map-marker-alt mr-2"></i> Thông tin nhận hàng</h6>
                                <p class="mb-1"><strong>{{ $selectedOrder->customer_name }}</strong>
                                    - {{ $selectedOrder->customer_phone }}</p>
                                <p class="mb-0 text-muted">{{ $selectedOrder->shipping_address }}
                                    , {{ $selectedOrder->shipping_ward }}, {{ $selectedOrder->shipping_province }}</p>
                            </div>

                            <h6 class="text-uppercase mb-3" style="color: #c9ab81; font-size: 14px;">
                                <i class="fas fa-utensils mr-2"></i> Món ăn đã đặt</h6>
                            <div class="table-responsive">
                                <table class="table table-borderless text-white">
                                    <thead style="border-bottom: 1px solid #333;">
                                    <tr>
                                        <th>Món</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($selectedOrder->items as $item)
                                        <tr style="border-bottom: 1px dashed #333;">
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->menuItem->thumbnail_url ?? asset('images/default-food.png') }}" alt="{{ $item->menuItem->name ?? 'Món ăn' }}" class="rounded mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <span class="d-block font-weight-bold">{{ $item->menuItem->name ?? 'Món ăn đã bị xóa' }}</span>
                                                        <small class="text-muted">{{ number_format($item->item_price) }}đ</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">{{ $item->quantity }}</td>
                                            <td class="align-middle text-right font-weight-bold">{{ number_format($item->item_price * $item->quantity) }}đ</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 pt-3" style="border-top: 1px solid #333;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tạm tính món:</span>
                                    <span>{{ number_format($selectedOrder->subtotal) }}đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Phí giao hàng:</span>
                                    <span>{{ number_format($selectedOrder->shipping_fee) }}đ</span>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <span class="text-uppercase font-weight-bold" style="color: #c9ab81;">Tổng thanh toán:</span>
                                    <span class="font-weight-bold" style="font-size: 18px; color: #c9ab81;">{{ number_format($selectedOrder->total_amount) }}đ</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="spinner-border" style="color: #c9ab81;" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

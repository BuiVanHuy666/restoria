<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.dashboard', [
    'title' => 'Đơn hàng',
    'header' => 'Lịch Sử Thưởng Thức',
    'description' => 'Xem lại danh sách những món ăn tinh túy Quý khách đã đặt tại Restoria.'
])]
class extends Component {
    //
};
?>

<div>
    @foreach(range(1, 3) as $item)
    <div class="order-card-premium">
        <div class="order-header">
            <div class="order-id">#RES-2026-{{ 8800 + $item }}</div>
            <div class="order-date">Ngày đặt: 22/04/2026 - 19:30</div>
        </div>

        <div class="order-body">
            <div class="order-dishes">
                <i class="fas fa-utensils mr-2" style="color: var(--main-color);"></i>
                Bò bít tết sốt rượu vang đỏ, Súp nấm bào ngư, Rượu vang Chateau Margaux...
            </div>

            <div class="order-price-group">
                <span class="order-total-label">Tổng thanh toán</span>
                <span class="order-total-value">2,450,000đ</span>
            </div>
        </div>

        <div class="order-footer mt-4 d-flex justify-content-between align-items-center">
                <span class="status-pill {{ $item == 1 ? 'pill-pending' : 'pill-completed' }}">
                    {{ $item == 1 ? 'Đang chuẩn bị' : 'Đã hoàn thành' }}
                </span>

            <a href="#" class="theme-btn btn-style-one btn-sm" style="padding: 8px 25px;">
                    <span class="btn-wrap">
                        <span class="text-one">Chi tiết đơn</span>
                        <span class="text-two">Chi tiết đơn</span>
                    </span>
            </a>
        </div>
    </div>
    @endforeach
</div>

<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.dashboard', [
    'title' => 'Tài khoản',
    'header' => 'Tổng Quan Tài Khoản',
    'description' => 'Quản lý thông tin hồ sơ và bảo mật để nhận những đặc quyền tốt nhất từ Restoria.'
])]
class extends Component {
    //
};
?>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="glass-widget-box">
            <h5 class="widget-title">Thông tin liên hệ</h5>
            <div class="widget-info-list">
                <p><strong>Họ tên:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Điện thoại:</strong> {{ auth()->user()->phone ?? 'Chưa cập nhật' }}</p>
            </div>

            <a href="#" class="theme-btn btn-style-one btn-sm mt-4">
                        <span class="btn-wrap">
                            <span class="text-one">Chỉnh sửa hồ sơ</span>
                            <span class="text-two">Chỉnh sửa hồ sơ</span>
                        </span>
            </a>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="glass-widget-box">
            <h5 class="widget-title">Thống kê nhanh</h5>

            <div class="widget-info-list">
                <p><strong>Đơn hàng chờ xử lý:</strong> <span class="badge badge-warning ml-2">2 đơn</span></p>
                <p><strong>Điểm tích lũy:</strong> <span class="stat-highlight ml-2">150</span> điểm</p>
            </div>
        </div>
    </div>
</div>

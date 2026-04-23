<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.dashboard', [
    'title' => 'Sổ địa chỉ',
    'header' => 'Địa chỉ của tôi',
    'description' => 'Quản lý các địa điểm giao hàng để Restoria phục vụ Quý khách nhanh chóng nhất.'
])]
class extends Component {
    //
};
?>

<div>
    <div class="mb-5 text-right">
        <a href="#" class="theme-btn btn-style-one btn-sm">
            <span class="btn-wrap">
                <span class="text-one"><i class="fas fa-plus mr-2"></i> Thêm địa chỉ mới</span>
                <span class="text-two"><i class="fas fa-plus mr-2"></i> Thêm địa chỉ mới</span>
            </span>
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="address-card-glass is-default">
                <span class="default-badge">Mặc định</span>
                <div class="address-name">Nhà riêng</div>
                <span class="address-phone">090 123 4567</span>
                <div class="address-detail">
                    Số 123, Đường Lê Lợi, Phường Bến Thành,<br>
                    Quận 1, TP. Hồ Chí Minh
                </div>
                <div class="address-actions">
                    <a href="#"><i class="far fa-edit mr-1"></i> Chỉnh sửa</a>
                    <button class="btn-delete"><i class="far fa-trash-alt mr-1"></i> Xóa</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="address-card-glass">
                <div class="address-name">Văn phòng làm việc</div>
                <span class="address-phone">028 3822 0000</span>
                <div class="address-detail">
                    Tòa nhà Landmark 81, Khu đô thị Vinhomes Central Park,<br>
                    Quận Bình Thạnh, TP. Hồ Chí Minh
                </div>
                <div class="address-actions">
                    <a href="#"><i class="far fa-edit mr-1"></i> Chỉnh sửa</a>
                    <button class="btn-delete"><i class="far fa-trash-alt mr-1"></i> Xóa</button>
                    <a href="#" style="margin-left: auto; color: var(--main-color);">Đặt làm mặc định</a>
                </div>
            </div>
        </div>
    </div>
</div>
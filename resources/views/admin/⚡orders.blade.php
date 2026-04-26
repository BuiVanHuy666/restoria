<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý đơn hàng',
    'heading' => 'Danh sách đơn hàng',
    'subheading' => 'Theo dõi và cập nhật trạng thái đơn hàng từ thực khách.'
])]
class extends Component {
    public $search = '';
    public $status = '';
};
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
        <div class="flex flex-1 gap-4 w-full">
            <flux:input
                wire:model.live="search"
                view="search"
                placeholder="Tìm mã đơn, tên khách..."
                class="max-w-xs"
            />

            <flux:select wire:model.live="status" placeholder="Trạng thái" class="max-w-[200px]">
                <flux:select.option value="">Tất cả trạng thái</flux:select.option>
                <flux:select.option value="pending">Đang chờ</flux:select.option>
                <flux:select.option value="processing">Đang chế biến</flux:select.option>
                <flux:select.option value="completed">Hoàn thành</flux:select.option>
                <flux:select.option value="cancelled">Đã hủy</flux:select.option>
            </flux:select>
        </div>

        <flux:button variant="primary" icon="plus">Tạo đơn tại quầy</flux:button>
    </div>

    <flux:card class="p-0 overflow-hidden">
        <flux:table>
            <flux:table.columns>
                <flux:table.column sortable>Mã đơn</flux:table.column>
                <flux:table.column sortable>Khách hàng</flux:table.column>
                <flux:table.column>Món ăn</flux:table.column>
                <flux:table.column sortable>Tổng tiền</flux:table.column>
                <flux:table.column>Trạng thái</flux:table.column>
                <flux:table.column>Thời gian đặt</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell class="font-medium text-zinc-900 dark:text-white">#RES-8905</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-col">
                            <span>Nguyễn Hoàng Nam</span>
                            <span class="text-xs text-zinc-500">090xxxx123</span>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell class="max-w-xs truncate">Bò bít tết sốt vang, Rượu vang đỏ Chateau...
                    </flux:table.cell>
                    <flux:table.cell class="font-bold">1,450,000đ</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="amber" icon="clock" size="sm">Đang chờ</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>09:45 - 24/04</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>
                            <flux:menu>
                                <flux:menu.item icon="eye">Xem chi tiết</flux:menu.item>
                                <flux:menu.item icon="check">Xác nhận đơn</flux:menu.item>
                                <flux:menu.item icon="printer">In hóa đơn</flux:menu.item>
                                <flux:menu.separator/>
                                <flux:menu.item icon="trash" variant="danger">Hủy đơn</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>

                <flux:table.row>
                    <flux:table.cell class="font-medium text-zinc-900 dark:text-white">#RES-8902</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-col">
                            <span>Lê Minh Tâm</span>
                            <span class="text-xs text-zinc-500">091xxxx456</span>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell class="max-w-xs truncate">Súp nấm bào ngư, Gan ngỗng Pháp (2)</flux:table.cell>
                    <flux:table.cell class="font-bold">890,000đ</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="blue" icon="arrow-path" size="sm">Chế biến</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>09:15 - 24/04</flux:table.cell>
                    <flux:table.cell>
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>
                    </flux:table.cell>
                </flux:table.row>

                <flux:table.row>
                    <flux:table.cell class="font-medium text-zinc-900 dark:text-white">#RES-8890</flux:table.cell>
                    <flux:table.cell>Phạm Anh Thư</flux:table.cell>
                    <flux:table.cell class="max-w-xs truncate">Cua hoàng đế sốt tiêu, Salad ức gà</flux:table.cell>
                    <flux:table.cell class="font-bold">3,200,000đ</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="emerald" icon="check" size="sm">Hoàn thành</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>Hôm qua</flux:table.cell>
                    <flux:table.cell>
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>
                    </flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
        </flux:table>

        <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
{{--            <flux:pagination :links="[]"/> --}}{{-- Thay bằng $orders->links() --}}
        </div>
    </flux:card>
</div>

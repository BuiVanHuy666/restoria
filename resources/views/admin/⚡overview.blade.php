<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.admin', [
    'title' => 'Tổng quan hệ thống',
    'heading' => 'Tổng quan',
    'subheading' => 'Thống kê hoạt động của nhà hàng Restoria trong hôm nay.'
])]
class extends Component {
    //
};
?>

<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <flux:card class="flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <flux:subheading>Tổng doanh thu</flux:subheading>
                    <flux:heading size="xl" class="mt-2 text-emerald-600 dark:text-emerald-400">24.500.000đ
                    </flux:heading>
                </div>
                <flux:icon.banknotes class="w-6 h-6 text-zinc-400"/>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-emerald-500 font-medium flex items-center gap-1">
                    <flux:icon.arrow-trending-up class="w-4 h-4"/> 15%
                </span>
                <span class="text-zinc-500">so với hôm qua</span>
            </div>
        </flux:card>

        <flux:card class="flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <flux:subheading>Đơn hàng mới</flux:subheading>
                    <flux:heading size="xl" class="mt-2">142</flux:heading>
                </div>
                <flux:icon.shopping-bag class="w-6 h-6 text-zinc-400"/>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-rose-500 font-medium flex items-center gap-1">
                    <flux:icon.arrow-trending-down class="w-4 h-4"/> 2%
                </span>
                <span class="text-zinc-500">so với hôm qua</span>
            </div>
        </flux:card>

        <flux:card class="flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <flux:subheading>Khách hàng mới</flux:subheading>
                    <flux:heading size="xl" class="mt-2">28</flux:heading>
                </div>
                <flux:icon.users class="w-6 h-6 text-zinc-400"/>
            </div>
            <div class="mt-4 text-sm text-zinc-500">
                <span class="text-emerald-500 font-medium">+5</span> khách hàng
            </div>
        </flux:card>

        <flux:card class="flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <flux:subheading>Bàn đang chờ</flux:subheading>
                    <flux:heading size="xl" class="mt-2 text-amber-500">5</flux:heading>
                </div>
                <flux:icon.clock class="w-6 h-6 text-zinc-400"/>
            </div>
            <div class="mt-4 text-sm text-zinc-500">
                Cập nhật lúc: {{ now()->format('H:i') }}
            </div>
        </flux:card>
    </div>

    <flux:card>
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">Đơn hàng cần xử lý</flux:heading>
            <flux:button size="sm" variant="outline" href="#">Xem tất cả</flux:button>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>Mã đơn</flux:table.column>
                <flux:table.column>Khách hàng</flux:table.column>
                <flux:table.column>Tổng tiền</flux:table.column>
                <flux:table.column>Trạng thái</flux:table.column>
                <flux:table.column>Thời gian</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell class="font-medium text-zinc-900 dark:text-white">#ORD-8902</flux:table.cell>
                    <flux:table.cell>Nguyễn Văn A</flux:table.cell>
                    <flux:table.cell>1.250.000đ</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="amber" icon="clock">Đang chờ</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>10 phút trước</flux:table.cell>
                    <flux:table.cell>
                        <flux:button variant="ghost" size="sm" icon="eye"/>
                    </flux:table.cell>
                </flux:table.row>

                <flux:table.row>
                    <flux:table.cell class="font-medium text-zinc-900 dark:text-white">#ORD-8901</flux:table.cell>
                    <flux:table.cell>Trần Thị B</flux:table.cell>
                    <flux:table.cell>850.000đ</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="blue" icon="arrow-path">Đang chế biến</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>45 phút trước</flux:table.cell>
                    <flux:table.cell>
                        <flux:button variant="ghost" size="sm" icon="eye"/>
                    </flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
        </flux:table>
    </flux:card>

</div>

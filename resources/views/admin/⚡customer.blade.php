<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý người dùng',
    'heading' => 'Tài khoản hệ thống',
    'subheading' => 'Quản lý thông tin khách hàng, nhân viên và phân quyền truy cập.'
])]
class extends Component
{
    public $search = '';
    public $role = '';
    public $status = '';
}
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
        <div class="flex flex-1 gap-4 w-full">
            <flux:input
                wire:model.live="search"
                view="search"
                placeholder="Tìm tên, email, số điện thoại..."
                class="max-w-xs"
            />

            <flux:select wire:model.live="role" placeholder="Vai trò" class="max-w-[180px]">
                <flux:select.option value="">Tất cả vai trò</flux:select.option>
                <flux:select.option value="admin">Quản trị viên</flux:select.option>
                <flux:select.option value="manager">Quản lý</flux:select.option>
                <flux:select.option value="staff">Nhân viên</flux:select.option>
                <flux:select.option value="customer">Khách hàng</flux:select.option>
            </flux:select>

            <flux:select wire:model.live="status" placeholder="Trạng thái" class="max-w-[180px]">
                <flux:select.option value="">Tất cả trạng thái</flux:select.option>
                <flux:select.option value="active">Đang hoạt động</flux:select.option>
                <flux:select.option value="banned">Bị khóa</flux:select.option>
            </flux:select>
        </div>

        <flux:button variant="primary" icon="user-plus">Thêm người dùng</flux:button>
    </div>

    <flux:card class="p-0 overflow-hidden">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Người dùng</flux:table.column>
                <flux:table.column>Vai trò</flux:table.column>
                <flux:table.column>Trạng thái</flux:table.column>
                <flux:table.column>Ngày tham gia</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell>
                        <div class="flex items-center gap-3">
                            <flux:profile avatar="https://ui-avatars.com/api/?name=Admin+Restoria&background=0D8ABC&color=fff" />
                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white">Trần Văn Quản Trị</div>
                                <div class="text-xs text-zinc-500">admin@restoria.vn</div>
                            </div>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="fuchsia" size="sm">Quản trị viên</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="emerald" size="sm">Hoạt động</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell class="text-zinc-500 text-sm">01/01/2026</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                            <flux:menu>
                                <flux:menu.item icon="pencil">Chỉnh sửa thông tin</flux:menu.item>
                                <flux:menu.item icon="shield-check">Đổi quyền hạn</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>

                <flux:table.row>
                    <flux:table.cell>
                        <div class="flex items-center gap-3">
                            <flux:profile avatar="https://ui-avatars.com/api/?name=Le+Thi+B&background=10B981&color=fff" />
                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white">Lê Thị Phục Vụ</div>
                                <div class="text-xs text-zinc-500">lethib@restoria.vn</div>
                            </div>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="blue" size="sm">Nhân viên</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="emerald" size="sm">Hoạt động</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell class="text-zinc-500 text-sm">15/02/2026</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                            <flux:menu>
                                <flux:menu.item icon="pencil">Chỉnh sửa thông tin</flux:menu.item>
                                <flux:menu.item icon="shield-check">Đổi quyền hạn</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="lock-closed" variant="danger">Khóa tài khoản</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>

                <flux:table.row>
                    <flux:table.cell>
                        <div class="flex items-center gap-3">
                            <flux:profile avatar="https://ui-avatars.com/api/?name=Nguyen+Van+Spam&background=F43F5E&color=fff" class="opacity-50 grayscale" />
                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white opacity-50">Nguyễn Văn Khách</div>
                                <div class="text-xs text-zinc-500">khach.spam@gmail.com</div>
                            </div>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="zinc" size="sm">Khách hàng</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="rose" size="sm">Bị khóa</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell class="text-zinc-500 text-sm">20/04/2026</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                            <flux:menu>
                                <flux:menu.item icon="eye">Xem lịch sử đơn</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="lock-open">Mở khóa tài khoản</flux:menu.item>
                                <flux:menu.item icon="trash" variant="danger">Xóa vĩnh viễn</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>

            </flux:table.rows>
        </flux:table>

        <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
            <div class="text-sm text-zinc-500 text-center">
                Tính năng phân trang sẽ hoạt động khi kết nối Database.
            </div>
        </div>
    </flux:card>
</div>

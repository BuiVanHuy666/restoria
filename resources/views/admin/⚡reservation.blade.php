<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Flux\Flux;

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý đặt bàn',
    'heading' => 'Danh sách đặt bàn',
    'subheading' => 'Xem và quản lý các yêu cầu đặt bàn từ khách hàng.'
])]
class extends Component {
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';

    public ?int $editId = null;
    public string $status = '';
    public ?int $deleteId = null;

    #[Computed]
    public function reservations()
    {
        return Reservation::query()
                          ->when($this->search, function ($q) {
                              $q
                                  ->where('customer_name', 'like', "%{$this->search}%")
                                  ->orWhere('phone_number', 'like', "%{$this->search}%");
                          })
                          ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
                          ->latest()
                          ->paginate(10);
    }

    public function editStatus($id): void
    {
        $reservation = Reservation::findOrFail($id);
        $this->editId = $reservation->id;

        // Fix: Lấy giá trị chuỗi (value) từ Enum để gán vào thuộc tính string $status
        $this->status = $reservation->status->value ?? $reservation->status;

        Flux::modal('status-modal')->show();
    }

    public function updateStatus(): void
    {
        $reservation = Reservation::findOrFail($this->editId);
        $reservation->update(['status' => $this->status]);

        Flux::toast('Đã cập nhật trạng thái đặt bàn.', variant: 'success');
        Flux::modal('status-modal')->close();
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        Flux::modal('delete-modal')->show();
    }

    public function destroy(): void
    {
        Reservation::find($this->deleteId)->delete();
        Flux::modal('delete-modal')->close();
        Flux::toast('Đã xóa yêu cầu đặt bàn.', variant: 'success');
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
        <div class="flex flex-1 gap-4 w-full">
            <flux:input wire:model.live.debounce.300ms="search" view="search" placeholder="Tìm tên hoặc số điện thoại..." class="max-w-xs"/>
            <flux:select wire:model.live="filterStatus" placeholder="Trạng thái">
                <flux:select.option value="">Tất cả trạng thái</flux:select.option>
                @foreach(App\Enums\ReservationStatus::cases() as $status)
                    <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <flux:card class="overflow-hidden">
        <flux:table :paginate="$this->reservations">
            <flux:table.columns>
                <flux:table.column>Khách hàng</flux:table.column>
                <flux:table.column>Thời gian</flux:table.column>
                <flux:table.column>Số khách</flux:table.column>
                <flux:table.column>Lời nhắn</flux:table.column>
                <flux:table.column>Trạng thái</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($this->reservations as $res)
                    <flux:table.row :key="$res->id">
                        <flux:table.cell>
                            <div class="font-medium text-zinc-900 dark:text-white">{{ $res->customer_name }}</div>
                            <div class="text-xs text-zinc-500">{{ $res->phone_number }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="text-sm">{{ \Carbon\Carbon::parse($res->reservation_date)->format('d/m/Y') }}</div>
                            <div class="text-xs text-zinc-500">{{ $res->reservation_time }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="zinc" size="sm" variant="subtle">{{ $res->guests }} người</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="text-xs italic truncate max-w-[200px]" title="{{ $res->message }}">
                                {{ $res->message ?: 'Không có lời nhắn' }}
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            {{-- Fix: Sử dụng trực tiếp hàm color() và label() từ Enum --}}
                            <flux:badge :color="$res->status->color()" size="sm" variant="subtle">
                                {{ $res->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>
                                <flux:menu>
                                    <flux:menu.item icon="pencil-square" wire:click="editStatus({{ $res->id }})">
                                        Cập nhật trạng thái
                                    </flux:menu.item>
                                    <flux:menu.separator/>
                                    <flux:menu.item icon="trash" variant="danger" wire:click="confirmDelete({{ $res->id }})">
                                        Xóa
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-12 text-zinc-500 italic">
                            Không tìm thấy yêu cầu nào.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="status-modal" class="md:w-110">
        <form wire:submit="updateStatus" class="space-y-6">
            <div>
                <flux:heading size="lg">Cập nhật trạng thái</flux:heading>
                <flux:subheading>Thay đổi tình trạng xử lý cho yêu cầu đặt bàn này.</flux:subheading>
            </div>

            <flux:select wire:model="status" label="Chọn trạng thái mới">
                @foreach(App\Enums\ReservationStatus::cases() as $status)
                    <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Lưu thay đổi</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="delete-modal" class="md:w-100">
        <div class="space-y-6">
            <flux:heading size="lg">Xác nhận xóa?</flux:heading>
            <p class="text-sm text-zinc-600">Dữ liệu đặt bàn này sẽ bị xóa vĩnh viễn.</p>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="destroy">Xóa ngay</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

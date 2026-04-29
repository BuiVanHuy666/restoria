<?php

namespace App\Livewire\Admin;

use App\Models\Promotion;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Flux\Flux;
use Illuminate\Support\Facades\DB;

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý khuyến mãi',
    'heading' => 'Chương trình ưu đãi',
    'subheading' => 'Thiết lập các mã giảm giá và chương trình khuyến mãi cho thực đơn.'
])]
class extends Component {
    use WithPagination;

    public string $search = '';
    public string $status = '';

    // Form fields
    public string $name = '';
    public string $description = '';
    public string $discount_type = 'percentage';
    public $discount_value;
    public $max_discount_amount;
    public string $apply_to = 'all';
    public $starts_at;
    public $ends_at;
    public bool $is_active = true;

    // Selection for categories/items
    public array $selected_categories = [];
    public array $selected_items = [];

    public bool $isEditMode = false;
    public ?int $editId = null;
    public ?int $deleteId = null;
    public ?int $selectedPromotionId = null;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'apply_to' => 'required|in:all,categories,items',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ];
    }

    #[Computed]
    public function promotions(): LengthAwarePaginator
    {
        return Promotion::query()
                        ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                        ->when($this->status !== '', function ($q) {
                            $this->status === 'active' ? $q->where('is_active', true) : $q->where('is_active', false);
                        })
                        ->latest()
                        ->paginate(10);
    }

    #[Computed]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    #[Computed]
    public function menuItems()
    {
        return MenuItem::orderBy('name')->get();
    }

    #[Computed]
    public function selectedPromotion(): ?Promotion
    {
        if (!$this->selectedPromotionId) {
            return null;
        }
        return Promotion::with(['categories', 'menuItems'])->find($this->selectedPromotionId);
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(
            [
                'editId',
                'name',
                'description',
                'discount_value',
                'max_discount_amount',
                'starts_at',
                'ends_at',
                'selected_categories',
                'selected_items',
                'isEditMode'
            ]
        );
        $this->discount_type = 'percentage';
        $this->apply_to = 'all';
        $this->is_active = true;

        Flux::modal('promotion-form-modal')->show();
    }

    public function edit($id): void
    {
        $this->resetValidation();
        $promotion = Promotion::with(['categories', 'menuItems'])->findOrFail($id);

        $this->editId = $promotion->id;
        $this->name = $promotion->name;
        $this->description = $promotion->description;
        $this->discount_type = $promotion->discount_type;
        $this->discount_value = $promotion->discount_value;
        $this->max_discount_amount = $promotion->max_discount_amount;
        $this->apply_to = $promotion->apply_to;
        $this->starts_at = $promotion->starts_at->format('Y-m-d\TH:i');
        $this->ends_at = $promotion->ends_at->format('Y-m-d\TH:i');
        $this->is_active = $promotion->is_active;

        $this->selected_categories = $promotion->categories->pluck('id')->toArray();
        $this->selected_items = $promotion->menuItems->pluck('id')->toArray();

        $this->isEditMode = true;
        Flux::modal('promotion-form-modal')->show();
    }

    public function save(): void
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $data = [
                    'name' => $this->name,
                    'description' => $this->description,
                    'discount_type' => $this->discount_type,
                    'discount_value' => $this->discount_value,
                    'max_discount_amount' => $this->max_discount_amount,
                    'apply_to' => $this->apply_to,
                    'starts_at' => $this->starts_at,
                    'ends_at' => $this->ends_at,
                    'is_active' => $this->is_active,
                ];

                if ($this->isEditMode) {
                    $promotion = Promotion::findOrFail($this->editId);
                    $promotion->update($data);
                } else {
                    $promotion = Promotion::create($data);
                }

                if ($this->apply_to === 'categories') {
                    $promotion->categories()->sync($this->selected_categories);
                    $promotion->menuItems()->detach();
                } elseif ($this->apply_to === 'items') {
                    $promotion->menuItems()->sync($this->selected_items);
                    $promotion->categories()->detach();
                } else {
                    $promotion->categories()->detach();
                    $promotion->menuItems()->detach();
                }
            });

            Flux::toast($this->isEditMode ? 'Cập nhật thành công!' : 'Thêm mới thành công!', variant: 'success');
            Flux::modal('promotion-form-modal')->close();
        } catch (\Exception $e) {
            Flux::toast('Lỗi: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function toggleStatus($id): void
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->update(['is_active' => !$promotion->is_active]);
        Flux::toast('Đã cập nhật trạng thái.', variant: 'success');
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        Flux::modal('promotion-delete-modal')->show();
    }

    public function destroy(): void
    {
        if ($this->deleteId) {
            Promotion::find($this->deleteId)->delete();
            Flux::modal('promotion-delete-modal')->close();
            Flux::toast('Đã xóa khuyến mãi.', variant: 'success');
        }
    }

    public function showDetail($id): void
    {
        $this->selectedPromotionId = $id;
        Flux::modal('promotion-detail-modal')->show();
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
        <div class="flex flex-1 gap-4 w-full">
            <flux:input wire:model.live.debounce.300ms="search" view="search" placeholder="Tìm tên chương trình..." class="max-w-xs"/>
            <flux:select wire:model.live="status" placeholder="Trạng thái" class="max-w-45">
                <flux:select.option value="">Tất cả trạng thái</flux:select.option>
                <flux:select.option value="active">Đang chạy</flux:select.option>
                <flux:select.option value="inactive">Tạm dừng</flux:select.option>
            </flux:select>
        </div>
        <flux:button variant="primary" icon="gift" wire:click="create">Tạo khuyến mãi</flux:button>
    </div>

    <flux:card class="overflow-hidden">
        <flux:table :paginate="$this->promotions">
            <flux:table.columns>
                <flux:table.column>Chương trình</flux:table.column>
                <flux:table.column>Mức giảm</flux:table.column>
                <flux:table.column>Phạm vi</flux:table.column>
                <flux:table.column>Thời hạn</flux:table.column>
                <flux:table.column>Trạng thái</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($this->promotions as $promo)
                    <flux:table.row :key="$promo->id">
                        <flux:table.cell>
                            <div>
                                <div class="font-medium text-zinc-900 dark:text-white cursor-pointer hover:text-emerald-600" wire:click="showDetail({{ $promo->id }})">
                                    {{ $promo->name }}
                                </div>
                                <div class="text-xs text-zinc-500 truncate max-w-xs">{{ $promo->description }}</div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="orange" size="sm">
                                {{ $promo->discount_type === 'percentage' ? $promo->discount_value.'%' : number_format($promo->discount_value).'đ' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="text-sm">
                                @if($promo->apply_to === 'all') Toàn bộ thực đơn
                                @elseif($promo->apply_to === 'categories') {{ $promo->categories()->count() }} danh mục
                                @else {{ $promo->menuItems()->count() }} món ăn
                                @endif
                            </span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="text-xs space-y-1">
                                <div class="flex items-center gap-1 text-zinc-600"><flux:icon.calendar class="w-3 h-3"/> {{ $promo->starts_at->format('d/m/y') }}</div>
                                <div class="flex items-center gap-1 text-zinc-400 font-medium"><flux:icon.clock class="w-3 h-3"/> {{ $promo->ends_at->format('d/m/y') }}</div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge :color="$promo->is_active ? 'emerald' : 'rose'" size="sm">
                                {{ $promo->is_active ? 'Đang chạy' : 'Tạm dừng' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>
                                <flux:menu>
                                    <flux:menu.item icon="eye" wire:click="showDetail({{ $promo->id }})">Chi tiết</flux:menu.item>
                                    <flux:menu.item icon="pencil" wire:click="edit({{ $promo->id }})">Sửa</flux:menu.item>
                                    <flux:menu.separator/>
                                    <flux:menu.item icon="trash" variant="danger" wire:click="confirmDelete({{ $promo->id }})">Xóa</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-12 text-zinc-500 italic">Không có dữ liệu.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="promotion-form-modal" class="md:w-175">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $isEditMode ? 'Cập nhật khuyến mãi' : 'Tạo khuyến mãi mới' }}</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Tên chương trình" class="md:col-span-2" required/>
                <flux:select wire:model.live="discount_type" label="Loại giảm giá">
                    <flux:select.option value="percentage">Phần trăm (%)</flux:select.option>
                    <flux:select.option value="fixed">Tiền mặt (VNĐ)</flux:select.option>
                </flux:select>
                <flux:input wire:model="discount_value" type="number" label="Giá trị giảm" required/>
                <flux:input wire:model="starts_at" type="datetime-local" label="Bắt đầu" required/>
                <flux:input wire:model="ends_at" type="datetime-local" label="Kết thúc" required/>
                <flux:select wire:model.live="apply_to" label="Áp dụng cho">
                    <flux:select.option value="all">Toàn bộ</flux:select.option>
                    <flux:select.option value="categories">Danh mục</flux:select.option>
                    <flux:select.option value="items">Món ăn</flux:select.option>
                </flux:select>
            </div>

            @if($apply_to === 'categories')
                <div class="p-4 border rounded-lg bg-zinc-50/50 grid grid-cols-2 gap-2">
                    @foreach($this->categories as $cat)
                        <flux:checkbox wire:model="selected_categories" value="{{ $cat->id }}" label="{{ $cat->name }}"/>
                    @endforeach
                </div>
            @endif

            @if($apply_to === 'items')
                <div class="p-4 border rounded-lg bg-zinc-50/50 max-h-40 overflow-y-auto space-y-2">
                    @foreach($this->menuItems as $item)
                        <flux:checkbox wire:model="selected_items" value="{{ $item->id }}" label="{{ $item->name }}"/>
                    @endforeach
                </div>
            @endif

            <flux:textarea wire:model="description" label="Mô tả" rows="2"/>
            <flux:switch wire:model="is_active" label="Kích hoạt ngay"/>
            <div class="flex justify-end gap-2 pt-4 border-t">
                <flux:modal.close><flux:button variant="ghost">Hủy</flux:button></flux:modal.close>
                <flux:button type="submit" variant="primary">Lưu</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="promotion-detail-modal" class="md:w-130">
        @if($this->selectedPromotion)
            <div class="space-y-6">
                <flux:heading size="xl">{{ $this->selectedPromotion->name }}</flux:heading>
                <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg text-sm italic">{{ $this->selectedPromotion->description }}</div>
                <div class="flex justify-end gap-2">
                    <flux:modal.close><flux:button variant="ghost">Đóng</flux:button></flux:modal.close>
                    <flux:button variant="primary" wire:click="edit({{ $this->selectedPromotion->id }})">Sửa</flux:button>
                </div>
            </div>
        @endif
    </flux:modal>

    <flux:modal name="promotion-delete-modal" class="md:w-110">
        <div class="space-y-6">
            <flux:heading size="lg">Xác nhận xóa?</flux:heading>
            <div class="flex justify-end gap-2">
                <flux:modal.close><flux:button variant="ghost">Hủy</flux:button></flux:modal.close>
                <flux:button variant="danger" wire:click="destroy">Xóa</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

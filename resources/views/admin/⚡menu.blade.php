<?php

use App\Enums\MenuItemStatus;
use App\Services\Admin\MenuItemService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Str;
use Flux\Flux;
use Livewire\WithPagination;

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý thực đơn',
    'heading' => 'Thực đơn nhà hàng',
    'subheading' => 'Quản lý danh sách món ăn, giá cả và tình trạng phục vụ.'
])]
class extends Component {
    use WithFileUploads, WithPagination;

    public string $search = '';
    public string $category = '';
    public string $status = '';

    public string $name = '';
    public string $code = '';
    public array $category_ids = [];
    public $price = '';
    public $item_status = 'available';
    public $description = '';
    public $image;

    public bool $is_new = false;
    public bool $is_popular = false;
    public bool $is_round_image = false;

    public bool $isEditMode = false;
    public ?int $editId = null;
    public ?string $existingImageUrl = null;

    public ?int $deleteId = null;
    public ?string $previewImageUrl = null;
    public ?int $selectedItemId = null;

    protected MenuItemService $menuItemService;

    public function boot(MenuItemService $menuItemService): void
    {
        $this->menuItemService = $menuItemService;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:menu_items,code,'.$this->editId,
            'category_ids' => 'required|array|min:1',
            'price' => 'required|integer|min:0',
            'item_status' => 'required',
            'image' => 'nullable|image|max:2048',
            'is_new' => 'boolean',
            'is_popular' => 'boolean',
            'is_round_image' => 'boolean',
        ];
    }

    public array $messages = [
        'name.required' => 'Vui lòng nhập tên món ăn.',
        'category_ids.required' => 'Vui lòng chọn ít nhất một danh mục.',
        'code.unique' => 'Mã món ăn này đã tồn tại.',
        'price.required' => 'Vui lòng nhập giá bán.',
        'image.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
    ];

    #[Computed]
    public function menuItems()
    {
        return $this->menuItemService->getList(
            search: $this->search,
            categoryId: $this->category !== '' ? (int)$this->category : null,
            status: $this->status !== '' ? $this->status : null
        );
    }

    #[Computed]
    public function categories()
    {
        return Category::orderBy('sort_order')->get();
    }

    #[Computed]
    public function selectedItem(): ?MenuItem
    {
        if (!$this->selectedItemId) return null;
        return MenuItem::with('categories')->find($this->selectedItemId);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function showDetail($id): void
    {
        $this->selectedItemId = $id;
        Flux::modal('item-detail-modal')->show();
    }

    public function previewImage($url): void
    {
        $this->previewImageUrl = $url;
        Flux::modal('image-preview-modal')->show();
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        Flux::modal('item-delete-modal')->show();
    }

    public function toggleQuickFlag($id, $field): void
    {
        $item = MenuItem::findOrFail($id);
        $item->update([$field => !$item->$field]);
        unset($this->selectedItem);

        Flux::toast('Đã cập nhật thuộc tính món ăn.', variant: 'success');
    }

    public function updateQuickStatus($id, $newStatus): void
    {
        $item = MenuItem::findOrFail($id);
        $item->update(['status' => $newStatus]);
        unset($this->selectedItem);

        Flux::toast('Đã cập nhật tình trạng phục vụ.', variant: 'success');
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(
            [
                'editId',
                'name',
                'code',
                'category_ids',
                'price',
                'description',
                'image',
                'existingImageUrl',
                'isEditMode',
                'is_new',
                'is_popular',
                'is_round_image'
            ]
        );
        $this->item_status = 'available';
        Flux::modal('menu-item-form-modal')->show();
    }

    public function edit($id): void
    {
        $this->resetValidation();
        $item = MenuItem::with('categories')->findOrFail($id);

        $this->editId = $item->id;
        $this->name = $item->name;
        $this->code = $item->code;
        $this->category_ids = $item->categories->pluck('id')->toArray();
        $this->price = $item->price;
        $this->item_status = $item->status->value ?? $item->status;
        $this->description = $item->description;
        $this->existingImageUrl = $item->thumbnailUrl;

        $this->is_new = (bool)$item->is_new;
        $this->is_popular = (bool)$item->is_popular;
        $this->is_round_image = (bool)$item->is_round_image;

        $this->image = null;
        $this->isEditMode = true;

        Flux::modal('menu-item-form-modal')->show();
    }

    public function save(): void
    {
        $this->validate();

        try {
            $data = [
                'category_ids' => $this->category_ids,
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'status' => $this->item_status,
                'is_new' => $this->is_new,
                'is_popular' => $this->is_popular,
                'is_round_image' => $this->is_round_image
            ];

            if ($this->isEditMode) {
                $this->menuItemService->update($this->editId, $data, $this->image);
                Flux::toast('Cập nhật thành công món ăn.', variant: 'success');
            } else {
                $this->menuItemService->store($data, $this->image);
                Flux::toast('Đã thêm món ăn mới.', variant: 'success');
            }

            $this->reset(
                [
                    'editId',
                    'name',
                    'code',
                    'category_ids',
                    'price',
                    'item_status',
                    'description',
                    'image',
                    'existingImageUrl',
                    'isEditMode',
                    'is_new',
                    'is_popular',
                    'is_round_image'
                ]
            );
            Flux::modal('menu-item-form-modal')->close();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi lưu món: '.$e->getMessage());
            Flux::toast('Sự cố: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function destroy(): void
    {
        if ($this->deleteId) {
            try {
                $this->menuItemService->destroy($this->deleteId);
                $this->deleteId = null;
                Flux::modal('item-delete-modal')->close();
                Flux::toast('Đã xóa món ăn.', variant: 'success');
            } catch (\Exception $e) {
                Flux::toast('Lỗi xóa: '.$e->getMessage(), variant: 'danger');
                Flux::modal('item-delete-modal')->close();
            }
        }
    }
}
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
        <div class="flex flex-1 gap-4 w-full">
            <flux:input wire:model.live="search" view="search" placeholder="Tìm tên món ăn..." class="max-w-xs"/>

            <flux:select wire:model.live="category" placeholder="Danh mục" class="max-w-45">
                <flux:select.option value="">Tất cả danh mục</flux:select.option>
                @foreach($this->categories as $cat)
                    <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="status" placeholder="Trạng thái" class="max-w-45">
                <flux:select.option value="">Tất cả trạng thái</flux:select.option>
                @foreach(MenuItemStatus::cases() as $statusEnum)
                    <flux:select.option value="{{ $statusEnum->value }}">{{ $statusEnum->label() }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create">Thêm món mới</flux:button>
    </div>

    <flux:card class="overflow-hidden">
        <flux:table :paginate="$this->menuItems">
            <flux:table.columns>
                <flux:table.column>Món ăn</flux:table.column>
                <flux:table.column>Danh mục</flux:table.column>
                <flux:table.column sortable wire:click="sortBy('price')">Giá bán</flux:table.column>
                <flux:table.column>Trạng thái</flux:table.column>
                <flux:table.column>Cập nhật</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($this->menuItems as $item)
                    <flux:table.row :key="$item->id">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->thumbnailUrl }}"
                                     wire:click.stop="previewImage('{{ $item->thumbnailUrl }}')"
                                     class="w-12 h-12 object-cover border border-zinc-200 dark:border-zinc-700 hover:scale-105 transition-all
                                     {{ $item->is_round_image ? 'rounded-full aspect-square' : 'rounded-lg' }}"
                                     alt="{{ $item->name }}"
                                     loading="lazy"
                                >
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-white cursor-pointer hover:text-emerald-600" wire:click="showDetail({{ $item->id }})">
                                        {{ $item->name }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-zinc-500 font-mono">{{ $item->code }}</span>
                                        @if($item->is_new)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">MỚI</span>
                                        @endif
                                        @if($item->is_popular)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700">HOT</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex flex-wrap gap-1">
                                @foreach($item->categories->take(2) as $c)
                                    <flux:badge color="zinc" size="sm" inset="top bottom">{{ $c->name }}</flux:badge>
                                @endforeach
                                @if($item->categories->count() > 2)
                                    <flux:badge color="zinc" size="sm">
                                        +{{ $item->categories->count() - 2 }}</flux:badge>
                                @endif
                            </div>
                        </flux:table.cell>

                        <flux:table.cell class="font-medium text-emerald-600">
                            {{ number_format($item->price) }}đ
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge color="{{ $item->status->color() }}" size="sm">{{ $item->status->label() }}</flux:badge>
                        </flux:table.cell>

                        <flux:table.cell class="text-zinc-500 text-sm">
                            {{ $item->updated_at->diffForHumans() }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>
                                <flux:menu>
                                    <flux:menu.item icon="eye" wire:click="showDetail({{ $item->id }})">Xem chi tiết
                                    </flux:menu.item>
                                    <flux:menu.item icon="pencil" wire:click="edit({{ $item->id }})">Chỉnh sửa
                                    </flux:menu.item>
                                    <flux:menu.separator/>
                                    <flux:menu.item icon="trash" variant="danger" wire:click="confirmDelete({{ $item->id }})">
                                        Xóa món
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center py-12 text-zinc-500">
                            <p>Không tìm thấy món ăn nào.</p>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="menu-item-form-modal" class="md:w-175">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $isEditMode ? 'Cập nhật món ăn' : 'Thêm món ăn mới' }}</flux:heading>
                <flux:subheading>Điền thông tin chi tiết cho thực đơn nhà hàng.</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Tên món ăn" required/>
                <flux:input wire:model="code" label="Mã món (SKU)" required/>
                <flux:input wire:model="price" type="number" label="Giá bán (VNĐ)" min="0" required/>
                <flux:select wire:model="item_status" label="Tình trạng" required>
                    @foreach(MenuItemStatus::cases() as $statusEnum)
                        <flux:select.option value="{{ $statusEnum->value }}">{{ $statusEnum->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <div class="space-y-2">
                <flux:label>Danh mục hiển thị (Chọn nhiều)</flux:label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 p-3 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50/30">
                    @foreach($this->categories as $cat)
                        <flux:checkbox wire:model="category_ids" value="{{ $cat->id }}" label="{{ $cat->name }}"/>
                    @endforeach
                </div>
                @error('category_ids') <span class="text-xs text-red-500 italic">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50/30">
                <flux:switch wire:model="is_new" label="Món mới" description="Nhãn xanh"/>
                <flux:switch wire:model="is_popular" label="Bán chạy" description="Nhãn cam"/>
                <flux:switch wire:model="is_round_image" label="Dùng đĩa tròn" description="Layout lơ lửng"/>
            </div>

            <flux:textarea wire:model="description" label="Mô tả chi tiết" rows="3"/>

            <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4">
                <flux:input wire:model.live="image" type="file" label="Hình ảnh minh họa" accept="image/*"/>
                @if ($image)
                    <div class="relative inline-block group">
                        <img src="{{ $image->temporaryUrl() }}" class="w-20 h-20 object-cover rounded-md border border-emerald-500" alt="">
                        <button type="button" wire:click="$set('image', null)" class="absolute -top-2 -right-2 bg-black text-white rounded-full p-1">
                            <flux:icon.x-mark class="w-3 h-3"/>
                        </button>
                    </div>
                @elseif ($existingImageUrl && !Str::contains($existingImageUrl, 'default_food.jpg'))
                    <img src="{{ $existingImageUrl }}" class="w-20 h-20 object-cover rounded-md border" alt="" loading="lazy">
                @endif
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Lưu dữ liệu</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="item-detail-modal" class="md:w-175">
        @if($this->selectedItem)
            <div class="space-y-6" wire:key="detail-item-{{ $this->selectedItem->id }}">
                <div>
                    <flux:heading size="xl">{{ $this->selectedItem->name }}</flux:heading>
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($this->selectedItem->categories as $c)
                            <flux:badge size="sm" color="zinc" inset="top bottom">{{ $c->name }}</flux:badge>
                        @endforeach
                    </div>
                </div>

                <flux:separator variant="subtle"/>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1 flex flex-col items-center">
                        <div class="relative inline-block {{ $this->selectedItem->is_round_image ? 'p-1 border-2 border-[#d2a349] rounded-full' : '' }}">
                            <img src="{{ $this->selectedItem->thumbnailUrl }}"
                                 class="object-cover {{ $this->selectedItem->is_round_image ? 'w-44 h-44 rounded-full aspect-square' : 'w-full aspect-4/3 rounded-xl' }}"
                                 alt="{{ $this->selectedItem->name }}"
                                 loading="lazy"
                            >

                            @if($this->selectedItem->is_popular || $this->selectedItem->is_new)
                                <span class="whitespace-nowrap absolute left-1/2 -translate-x-1/2 -bottom-3 z-10 px-4 py-1 text-[11px] font-bold uppercase text-black"
                                      style="background-color: {{ $this->selectedItem->is_popular ? '#d2a349' : '#10b981' }}; min-width: 90px; text-align: center;">
                                {{ $this->selectedItem->is_popular ? 'Bán chạy' : 'Món mới' }}
                            </span>
                            @endif
                        </div>
                        <span class="text-[10px] text-zinc-400 mt-8 italic uppercase tracking-widest">Layout Preview</span>
                    </div>

                    <div class="md:col-span-2 space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Mã món</div>
                                <div class="font-mono text-sm bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded inline-block">{{ $this->selectedItem->code }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Giá bán</div>
                                <div class="font-bold text-lg text-emerald-600">{{ number_format($this->selectedItem->price) }}đ</div>
                            </div>
                        </div>

                        <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50/50 dark:bg-zinc-800/30 space-y-4">
                            <div class="text-xs text-zinc-500 uppercase tracking-wider font-bold border-b border-zinc-200 dark:border-zinc-700 pb-2">Thiết lập nhanh</div>

                            <div>
                                <flux:label size="sm" class="mb-2 block">Tình trạng phục vụ</flux:label>
                                <flux:select wire:change="updateQuickStatus({{ $this->selectedItem->id }}, $event.target.value)" size="sm">
                                    @foreach(App\Enums\MenuItemStatus::cases() as $statusEnum)
                                        <flux:select.option value="{{ $statusEnum->value }}" :selected="$this->selectedItem->status->value === $statusEnum->value">
                                            {{ $statusEnum->label() }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>

                            <div class="flex flex-col gap-3 pt-2">
                                <flux:switch
                                    wire:click="toggleQuickFlag({{ $this->selectedItem->id }}, 'is_new')"
                                    :checked="$this->selectedItem->is_new"
                                    label="Đánh dấu Món mới"
                                    description="Hiện nhãn xanh" />

                                <flux:switch
                                    wire:click="toggleQuickFlag({{ $this->selectedItem->id }}, 'is_popular')"
                                    :checked="$this->selectedItem->is_popular"
                                    label="Đánh dấu Bán chạy"
                                    description="Hiện nhãn cam" />

                                <flux:switch
                                    wire:click="toggleQuickFlag({{ $this->selectedItem->id }}, 'is_round_image')"
                                    :checked="$this->selectedItem->is_round_image"
                                    label="Dùng đĩa tròn"
                                    description="Bật layout đĩa tròn lơ lửng" />
                            </div>
                        </div>

                        <div class="bg-zinc-50 dark:bg-zinc-800/50 p-3 rounded-lg border italic text-zinc-600 dark:text-zinc-400 text-sm">
                            "{{ $this->selectedItem->description ?? 'Chưa có mô tả chi tiết cho món ăn này.' }}"
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <flux:modal.close><flux:button variant="ghost">Đóng</flux:button></flux:modal.close>
                    <flux:button variant="primary" icon="pencil" wire:click="edit({{ $this->selectedItem->id }})">Chỉnh sửa chi tiết</flux:button>
                </div>
            </div>
        @endif
    </flux:modal>

    <flux:modal name="item-delete-modal" class="md:w-110">
        <div class="space-y-6">
            <flux:heading size="lg">Xác nhận xóa</flux:heading>
            <p class="text-zinc-600">Hành động này sẽ xóa vĩnh viễn món ăn khỏi hệ thống. Ông giáo có chắc không?</p>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="destroy">Vâng, xóa món này</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="image-preview-modal" class="p-2 md:w-auto max-w-[90vw] md:max-w-200 bg-transparent border-none shadow-none">
        @if($previewImageUrl)
            <div class="relative flex justify-center group">
                <img src="{{ $previewImageUrl }}" class="w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
                     alt=""
                     loading="lazy"
                >
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <flux:modal.close>
                        <flux:button variant="subtle" size="sm" icon="x-mark" class="bg-black/50 text-white rounded-full"/>
                    </flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>
</div>

<?php

use App\Services\Admin\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Flux\Flux;

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý danh mục',
    'heading' => 'Danh mục thực đơn',
    'subheading' => 'Xem chi tiết và quản lý các nhóm món ăn của nhà hàng.'
])]
class extends Component {
    use WithFileUploads;

    public string $search = '';

    public string $name = '';
    public int $sort_order;
    public $thumbnail;

    public bool $isEditMode = false;
    public string|int|null $editId = null;
    public $existingThumbnailUrl = null;

    public $selectedCategoryId;
    public $previewImageUrl = null;

    public string|int|null $deleteId = null;

    protected CategoryService $categoryService;

    public function boot(CategoryService $categoryService): void
    {
        $this->categoryService = $categoryService;
    }

    #[Computed]
    public function categories()
    {
        return $this->categoryService->getCategoriesList($this->search);
    }

    #[Computed]
    public function selectedCategory(): Model|Collection|Category|null
    {
        if (!$this->selectedCategoryId) return null;

        return Category::with('menuItems')->find($this->selectedCategoryId);
    }

    public function showDetail($id): void
    {
        $this->selectedCategoryId = $id;
        Flux::modal('category-detail-modal')->show();
    }

    public function previewImage($url): void
    {
        $this->previewImageUrl = $url;
        Flux::modal('image-preview-modal')->show();
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        Flux::modal('category-delete-modal')->show();
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['editId', 'name', 'sort_order', 'thumbnail', 'existingThumbnailUrl', 'isEditMode']);
        $this->isEditMode = false;
        $this->sort_order = (Category::max('sort_order') ?? -1) + 1;

        Flux::modal('category-form-modal')->show();
    }

    public function edit($id): void
    {
        $this->resetValidation();
        $category = Category::findOrFail($id);

        $this->editId = $category->id;
        $this->name = $category->name;
        $this->sort_order = $category->sort_order;
        $this->existingThumbnailUrl = $category->thumbnail_url;
        $this->thumbnail = null;
        $this->isEditMode = true;

        Flux::modal('category-form-modal')->show();
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$this->editId,
            'sort_order' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục này đã tồn tại trong hệ thống.',
            'sort_order.min' => 'Thứ tự hiển thị không được là số âm.',
        ]);

        try {
            if ($this->isEditMode) {
                $this->categoryService->update(
                    id: $this->editId,
                    data: [
                        'name' => $this->name,
                        'sort_order' => $this->sort_order,
                    ],
                    thumbnailFile: $this->thumbnail
                );

                Flux::toast(
                    text: 'Thông tin danh mục đã được cập nhật.',
                    heading: 'Cập nhật thành công!',
                    variant: 'success',
                );
            } else {
                $this->categoryService->store(
                    data: [
                        'name' => $this->name,
                        'sort_order' => $this->sort_order,
                    ],
                    thumbnailFile: $this->thumbnail
                );

                Flux::toast(
                    text: 'Danh mục mới đã được thêm vào thực đơn.',
                    heading: 'Thêm mới thành công!',
                    variant: 'success',
                );
            }

            $this->reset(['editId', 'name', 'sort_order', 'thumbnail', 'existingThumbnailUrl', 'isEditMode']);
            Flux::modal('category-form-modal')->close();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::driver('crud')->error('Lỗi khi tạo danh mục: '.$e->getMessage());
            Flux::toast(
                text: 'Đã xảy ra sự cố: '.$e->getMessage(),
                heading: 'Thất bại!',
                variant: 'danger',
            );
        }
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            try {
                $this->categoryService->destroy($this->deleteId);

                $this->deleteId = null;
                Flux::modal('category-delete-modal')->close();

                Flux::toast(
                    text: 'Danh mục của bạn đã được xóa thành công.',
                    heading: 'Xóa thành công!',
                    variant: 'success',
                );
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::driver('crud')->error('Lỗi khi xóa danh mục', $e->getMessage());
                Flux::toast(
                    text: 'Không thể xóa danh mục này: '.$e->getMessage(),
                    heading: 'Lỗi xóa dữ liệu!',
                    variant: 'danger',
                );

                $this->deleteId = null;
                Flux::modal('category-delete-modal')->close();
            }
        }
    }
}
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
        <div class="flex flex-1 gap-4 w-full">
            <flux:input wire:model.live="search" view="search" placeholder="Tìm tên danh mục..." class="max-w-xs"/>
        </div>

        <div>
            <flux:button variant="primary" icon="folder-plus" wire:click="create">Thêm danh mục</flux:button>
        </div>
    </div>

    <flux:card class="overflow-hidden">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Thứ tự</flux:table.column>
                <flux:table.column>Tên danh mục</flux:table.column>
                <flux:table.column>Đường dẫn</flux:table.column>
                <flux:table.column>Số món</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($this->categories as $category)
                    <flux:table.row>
                        <flux:table.cell>
                            <flux:badge color="zinc" size="sm">{{ $category->sort_order }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-3 cursor-pointer" wire:click="showDetail({{ $category->id }})">
                                <img src="{{ $category->thumbnail_url }}"
                                     wire:click.stop="previewImage('{{ $category->thumbnail_url }}')"
                                     class="w-10 h-10 rounded-md object-cover border border-zinc-200 dark:border-zinc-700 hover:scale-110 transition-transform cursor-zoom-in"
                                     alt="{{ $category->name }}"
                                     loading="lazy">
                                <span class="font-medium">{{ $category->name }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 italic text-sm">/{{ $category->slug }}</flux:table.cell>
                        <flux:table.cell>{{ $category->menuItems->count() }} món</flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>
                                <flux:menu>
                                    <flux:menu.item icon="eye" wire:click="showDetail({{ $category->id }})">Xem chi
                                        tiết
                                    </flux:menu.item>
                                    <flux:menu.item icon="pencil" wire:click="edit({{ $category->id }})">Chỉnh sửa
                                    </flux:menu.item>
                                    <flux:menu.separator/>
                                    <flux:menu.item icon="trash" variant="danger" wire:click="confirmDelete({{ $category->id }})">
                                        Xóa
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="category-detail-modal" class="md:w-175">
        @if($this->selectedCategory)
            <div class="space-y-6">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-4">
                        <img src="{{ $this->selectedCategory->thumbnail_url }}"
                             wire:click="previewImage('{{ $this->selectedCategory->thumbnail_url }}')"
                             class="w-16 h-16 rounded-lg object-cover border-2 border-white dark:border-zinc-700 shadow-md cursor-zoom-in hover:opacity-80 transition-opacity"
                             loading="lazy"
                        >
                        <div>
                            <flux:heading size="xl">{{ $this->selectedCategory->name }}</flux:heading>
                            <flux:subheading>Đường dẫn:
                                <span class="font-mono">/{{ $this->selectedCategory->slug }}</span></flux:subheading>
                        </div>
                    </div>
                    <flux:badge color="zinc">Thứ tự: {{ $this->selectedCategory->sort_order }}</flux:badge>
                </div>

                <flux:separator/>

                <div>
                    <flux:heading size="sm" class="mb-4">Danh sách món ăn
                        ({{ $this->selectedCategory->menuItems->count() }})
                    </flux:heading>

                    <div class="max-h-75 overflow-y-auto pr-2 custom-scrollbar">
                        <flux:table>
                            <flux:table.rows>
                                @forelse($this->selectedCategory->menuItems as $item)
                                    <flux:table.row>
                                        <flux:table.cell>
                                            <div class="flex items-center gap-3">
                                                <img wire:click.stop="previewImage('{{ $item->thumbnail_url }}')"
                                                     src="{{ $item->thumbnail_url ?? asset('images/default-food.jpg') }}"
                                                     class="w-8 h-8 rounded object-cover"
                                                     loading="lazy">
                                                <span class="text-sm font-medium">{{ $item->name }}</span>
                                            </div>
                                        </flux:table.cell>
                                        <flux:table.cell class="text-sm">{{ number_format($item->price) }}đ
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            <flux:badge size="sm" color="{{ $item->status->color() }}">
                                                {{ $item->status->label() }}
                                            </flux:badge>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @empty
                                    <flux:table.row>
                                        <flux:table.cell colspan="3" class="text-center py-4 text-zinc-500 text-sm italic">
                                            Chưa có món ăn nào trong danh mục này.
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforelse
                            </flux:table.rows>
                        </flux:table>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <flux:modal.close>
                        <flux:button variant="ghost">Đóng</flux:button>
                    </flux:modal.close>
                    <flux:button variant="primary" icon="pencil" wire:click="edit({{ $this->selectedCategory->id }})">
                        Chỉnh sửa danh mục
                    </flux:button>
                </div>
            </div>
        @else
            <div class="flex justify-center py-12">
                <flux:icon.arrow-path class="animate-spin text-zinc-300"/>
            </div>
        @endif
    </flux:modal>

    <flux:modal name="category-form-modal" class="md:w-150">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ $isEditMode ? 'Cập nhật danh mục' : 'Thêm danh mục mới' }}
                </flux:heading>
                <flux:subheading>
                    {{ $isEditMode ? 'Chỉnh sửa thông tin nhóm món ăn hiện tại.' : 'Tạo nhóm mới để phân loại các món ăn trong thực đơn.' }}
                </flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Tên danh mục" placeholder="VD: Khai vị, Món chính..." required/>
                <flux:input wire:model="sort_order" type="number" label="Thứ tự hiển thị" placeholder="VD: 0" min="0" required/>
            </div>

            <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4">
                <div>
                    <flux:heading size="sm">Hình ảnh đại diện (Thumbnail)</flux:heading>
                    <flux:subheading size="sm">Hiển thị ở trang đặt món của khách hàng.</flux:subheading>
                </div>

                <flux:input wire:model.live="thumbnail" type="file" label="Tải ảnh lên từ máy" accept="image/*"/>

                @if ($thumbnail)
                    <div class="mt-2 p-2 bg-zinc-50 dark:bg-zinc-800 rounded-lg inline-block relative group">
                        <img src="{{ $thumbnail->temporaryUrl() }}" class="w-16 h-16 object-cover rounded-md border border-emerald-500 shadow-sm" alt="Preview" loading="lazy">
                        <span class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold">Mới</span>

                        <button type="button" wire:click="$set('thumbnail', null)" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                            <flux:icon.x-mark class="w-4 h-4"/>
                        </button>
                    </div>
                @elseif ($existingThumbnailUrl && $existingThumbnailUrl !== asset('images/default-category.png'))
                    <div class="mt-2 p-2 bg-zinc-50 dark:bg-zinc-800 rounded-lg inline-block">
                        <span class="text-xs text-zinc-500 mb-2 block">Ảnh hiện tại:</span>
                        <img src="{{ $existingThumbnailUrl }}" class="w-16 h-16 object-cover rounded-md border border-zinc-200 shadow-sm" alt="Current" loading="lazy">
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">
                    {{ $isEditMode ? 'Lưu thay đổi' : 'Lưu danh mục' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="image-preview-modal" class="p-2 md:w-auto max-w-[90vw] md:max-w-200 bg-transparent border-none shadow-none">
        @if($previewImageUrl)
            <div class="relative flex justify-center group">
                <img src="{{ $previewImageUrl }}" class="w-full max-h-[85vh] object-contain rounded-lg shadow-2xl" alt="Phóng to ảnh" loading="lazy">

                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <flux:modal.close>
                        <flux:button variant="subtle" size="sm" icon="x-mark" class="bg-black/50 hover:bg-black/80 text-white border-none rounded-full backdrop-blur-sm"/>
                    </flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>

    <flux:modal name="category-delete-modal" class="md:w-110">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Xác nhận xóa danh mục</flux:heading>
                <flux:subheading>
                    Ông giáo có chắc chắn muốn xóa danh mục này không? Hành động này không thể hoàn tác. Các ảnh đại
                    diện (nếu có) cũng sẽ bị xóa vĩnh viễn khỏi hệ thống.
                </flux:subheading>
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="delete">Vâng, xóa danh mục</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

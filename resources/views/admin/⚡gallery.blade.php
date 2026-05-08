<?php

namespace App\Livewire\Admin;

use App\Models\Gallery;
use App\Services\Core\ImageUploadService;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý thư viện ảnh',
    'heading' => 'Thư viện hình ảnh',
    'subheading' => 'Quản lý các hình ảnh hiển thị trên website (Không gian, Món ăn, Thực khách).'
])]
class extends Component {
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterCategory = '';

    public $image;
    public $existingImage = '';
    public $title = '';
    public $category = 'space';
    public $is_active = true;

    public bool $isEditMode = false;
    public ?int $editId = null;
    public ?int $deleteId = null;

    #[Computed]
    public function images(): LengthAwarePaginator
    {
        return Gallery::query()
                      ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
                      ->when($this->filterCategory, fn($q) => $q->where('category', $this->filterCategory))
                      ->latest()
                      ->paginate(12);
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['image', 'existingImage', 'title', 'category', 'isEditMode', 'editId']);
        $this->category = 'space';
        Flux::modal('gallery-form-modal')->show();
    }

    public function edit($id): void
    {
        $this->resetValidation();
        $gallery = Gallery::findOrFail($id);

        $this->editId = $gallery->id;
        $this->title = $gallery->title;
        $this->category = $gallery->category;
        $this->is_active = $gallery->is_active;

        $this->image = null;
        $this->existingImage = $gallery->image_path;

        $this->isEditMode = true;
        Flux::modal('gallery-form-modal')->show();
    }

    public function save(): void
    {
        $imageService = app(ImageUploadService::class);

        $rules = [
            'title' => 'nullable|string|max:255',
            'category' => 'required|in:space,food,guest',
            'is_active' => 'boolean',
        ];

        if (!$this->isEditMode || $this->image) {
            $rules['image'] = 'required|image|max:2048';
        }

        $this->validate($rules);

        try {
            $data = [
                'title' => $this->title,
                'category' => $this->category,
                'is_active' => $this->is_active,
            ];

            if ($this->image) {
                $filename = $imageService->handleUpload(
                    file: $this->image,
                    path: Gallery::GALLERIES_PATH,
                );

                $data['image_path'] = Gallery::GALLERIES_PATH.$filename;
            }

            if ($this->isEditMode) {
                $gallery = Gallery::findOrFail($this->editId);

                if ($this->image) {
                    $imageService->deleteImage($gallery->image_path);
                }

                $gallery->update($data);
            } else {
                Gallery::create($data);
            }

            Flux::toast($this->isEditMode ? 'Cập nhật thành công!' : 'Tải lên thành công!', variant: 'success');
            Flux::modal('gallery-form-modal')->close();
            $this->reset(['image']);
        } catch (\Exception $e) {
            Flux::toast('Lỗi: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        Flux::modal('gallery-delete-modal')->show();
    }

    public function destroy(): void
    {
        $imageService = app(ImageUploadService::class);
        $gallery = Gallery::find($this->deleteId);

        if ($gallery) {
            $imageService->deleteImage($gallery->image_path);
            $gallery->delete();

            Flux::modal('gallery-delete-modal')->close();
            Flux::toast('Đã xóa hình ảnh.', variant: 'success');
        }
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
        <div class="flex flex-1 gap-4 w-full">
            <flux:input wire:model.live.debounce.300ms="search" view="search" placeholder="Tìm kiếm..."/>

            <flux:select wire:model.live="filterCategory" placeholder="Tất cả danh mục">
                <flux:select.option value="">Tất cả</flux:select.option>
                @foreach(Gallery::GALLERY_TYPES as $key => $label)
                    <flux:select.option value="{{ $key }}">{{ $label }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="create">Thêm ảnh</flux:button>
    </div>

    <flux:card class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($this->images as $item)
            <div wire:key="gallery-item-{{ $item->id }}" class="relative group border rounded-lg overflow-hidden bg-zinc-50">
                <img src="{{ asset('storage/' . $item->image_path) }}" class="aspect-square object-cover w-full" alt="">
                <div class="p-2 bg-white dark:bg-zinc-800 border-t flex justify-between items-center">
                    <flux:badge size="sm" variant="subtle">
                        {{ Gallery::GALLERY_TYPES[$item->category] ?? $item->category }}
                    </flux:badge>
                    <flux:dropdown>
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>
                        <flux:menu>
                            <flux:menu.item icon="pencil" wire:click="edit({{ $item->id }})">Sửa</flux:menu.item>
                            <flux:menu.item icon="trash" variant="danger" wire:click="confirmDelete({{ $item->id }})">Xóa</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        @endforeach
    </flux:card>

    <flux:modal name="gallery-form-modal" class="md:w-140">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">Thông tin hình ảnh</flux:heading>
                <flux:subheading>Tải lên và phân loại hình ảnh cho thư viện của bạn.</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select wire:model="category" label="Danh mục áp dụng">
                    @foreach(App\Models\Gallery::GALLERY_TYPES as $key => $label)
                        <flux:select.option value="{{ $key }}">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="title" label="Tiêu đề (không bắt buộc)" placeholder="Nhập tiêu đề ảnh..." />
            </div>

            <div class="space-y-3">
                <flux:label>Hình ảnh tải lên</flux:label>

                <div
                    x-data="{ dragging: false }"
                    x-on:dragover.prevent="dragging = true"
                    x-on:dragleave.prevent="dragging = false"
                    x-on:drop.prevent="dragging = false"
                    class="relative"
                >
                    <label
                        for="image-upload"
                        :class="{ 'border-emerald-500 bg-emerald-50/20 dark:bg-emerald-500/5': dragging, 'border-zinc-300 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-900/50': !dragging }"
                        class="group relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-2xl cursor-pointer hover:border-emerald-500 hover:bg-emerald-50/10 dark:hover:bg-emerald-500/5 transition-all duration-200"
                    >
                        <div class="flex flex-col items-center justify-center px-4 text-center">
                            @if ($image)
                                <div class="relative group/preview mb-3">
                                    <img src="{{ $image->temporaryUrl() }}" class="h-40 w-auto object-cover rounded-xl shadow-lg border-4 border-white dark:border-zinc-800">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                        <flux:icon.pencil-square class="w-8 h-8 text-white"/>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Ảnh mới đã sẵn sàng!</p>
                            @elseif ($isEditMode && $existingImage)
                                <div class="relative group/preview mb-3">
                                    <img src="{{ asset('storage/' . $existingImage) }}" class="h-40 w-auto object-cover rounded-xl shadow-lg border-4 border-white dark:border-zinc-800">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                        <flux:icon.pencil-square class="w-8 h-8 text-white"/>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Đang sử dụng ảnh hiện tại</p>
                                <p class="text-xs text-zinc-500 mt-1">Nhấp để thay đổi ảnh mới</p>
                            @else
                                <div class="mb-4 p-4 bg-white dark:bg-zinc-800 rounded-2xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                                    <flux:icon.arrow-up-tray class="w-10 h-10 text-zinc-400 group-hover:text-emerald-500 transition-colors"/>
                                </div>
                                <p class="text-sm text-zinc-700 dark:text-zinc-300">
                                    <span class="font-bold text-emerald-600">Nhấp để tải lên</span> hoặc kéo thả ảnh vào đây
                                </p>
                                <p class="text-xs text-zinc-500 mt-2">Định dạng hỗ trợ: WEBP, PNG, JPG (Tối đa 2MB)</p>
                            @endif
                        </div>

                        <input id="image-upload" type="file" wire:model="image" class="hidden" accept="image/*" />
                    </label>

                    <div wire:loading wire:target="image" class="absolute inset-0 bg-zinc-900/60 backdrop-blur-sm rounded-2xl flex items-center justify-center z-10">
                        <div class="flex flex-col items-center bg-white dark:bg-zinc-800 p-4 rounded-xl shadow-xl">
                            <svg class="animate-spin h-6 w-6 text-emerald-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-bold text-zinc-700 dark:text-zinc-200">Đang xử lý ảnh...</span>
                        </div>
                    </div>
                </div>

                @error('image')
                <p class="text-xs text-rose-500 font-medium mt-2 flex items-center gap-1">
                    <flux:icon.exclamation-circle class="w-3 h-3"/> {{ $message }}
                </p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy bỏ</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" icon="check" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Lưu hình ảnh</span>
                    <span wire:loading wire:target="save">Đang lưu...</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="gallery-delete-modal" class="md:w-110">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Xác nhận xóa ảnh?</flux:heading>
                <flux:subheading>Hành động này sẽ xóa vĩnh viễn hình ảnh khỏi hệ thống và không thể hoàn tác.</flux:subheading>
            </div>

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy bỏ</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="destroy">Xác nhận xóa</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

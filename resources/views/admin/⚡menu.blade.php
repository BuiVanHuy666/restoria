<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Str;
use Flux\Flux; // Khai báo facade của Flux để điều khiển đóng/mở Modal

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý thực đơn',
    'heading' => 'Thực đơn nhà hàng',
    'subheading' => 'Quản lý danh sách món ăn, giá cả và tình trạng phục vụ.'
])]
class extends Component
{
    use WithFileUploads;

    public $search = '';
    public $category = '';
    public $status = '';

    public $name = '';
    public $code = '';
    public $category_id = '';
    public $price = '';
    public $item_status = 'available'; // Dùng tên biến khác để không trùng với bộ lọc status
    public $description = '';
    public $image;

    // Lấy danh sách Categories từ Database để đổ vào thẻ Select
    #[Computed]
    public function categories()
    {
        // Trả về danh sách danh mục (đảm bảo ông đã có dữ liệu trong bảng categories nhé)
        return Category::orderBy('sort_order')->get();
    }

    // Hàm xử lý khi bấm nút "Lưu món ăn"
    public function save()
    {
        // 1. Kiểm tra tính hợp lệ của dữ liệu (Validation)
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:menu_items,code',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'item_status' => 'required',
            'image' => 'nullable|image|max:2048', // Ảnh tối đa 2MB
        ], [
            'code.unique' => 'Mã món ăn này đã tồn tại trong hệ thống.',
            'category_id.required' => 'Vui lòng chọn một danh mục.',
        ]);

        // 2. Xử lý lưu ảnh vào thư mục storage/app/public/menu-items
        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('menu-items', 'public');
        }

        // 3. Lưu vào Database
        MenuItem::create([
            'category_id' => $this->category_id,
            'code' => $this->code,
            'name' => $this->name,
            'slug' => Str::slug($this->name), // Tự động tạo slug thân thiện
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->item_status,
            'image' => $imagePath,
        ]);

        // 4. Reset lại các ô nhập liệu cho sạch sẽ
        $this->reset(['name', 'code', 'category_id', 'price', 'item_status', 'description', 'image']);

        // 5. Đóng Modal lại bằng API của Flux
        Flux::modal('create-menu-item-modal')->close();

        // (Tùy chọn) Có thể bắn một thông báo Toast thành công ở đây
    }
}
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
        <div class="flex flex-1 gap-4 w-full">
            <flux:input wire:model.live="search" view="search" placeholder="Tìm tên món ăn..." class="max-w-xs" />

            <flux:select wire:model.live="category" placeholder="Danh mục" class="max-w-[180px]">
                <flux:select.option value="">Tất cả danh mục</flux:select.option>
                @foreach($this->categories as $cat)
                    <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="status" placeholder="Trạng thái" class="max-w-[180px]">
                <flux:select.option value="">Tất cả trạng thái</flux:select.option>
                @foreach(\App\Enums\MenuItemStatus::cases() as $statusEnum)
                    <flux:select.option value="{{ $statusEnum->value }}">{{ $statusEnum->label() }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <flux:modal.trigger name="create-menu-item-modal">
            <flux:button variant="primary" icon="plus">Thêm món mới</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:card class="overflow-hidden">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Món ăn</flux:table.column>
                <flux:table.column>Danh mục</flux:table.column>
                <flux:table.column sortable>Giá bán</flux:table.column>
                <flux:table.column>Trạng thái</flux:table.column>
                <flux:table.column>Cập nhật lần cuối</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
            </flux:table.rows>
        </flux:table>

        <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
            <div class="text-sm text-zinc-500 text-center">
                Tính năng phân trang sẽ hoạt động khi kết nối Database.
            </div>
        </div>
    </flux:card>

    <flux:modal name="create-menu-item-modal" class="md:w-[700px]">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">Thêm món ăn mới</flux:heading>
                <flux:subheading>Điền thông tin chi tiết cho món ăn mới đưa vào thực đơn.</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Tên món ăn" placeholder="VD: Bò bít tết sốt tiêu đen" required />
                <flux:input wire:model="code" label="Mã món (SKU)" placeholder="VD: F-BEEF-05" required />

                <flux:select wire:model="category_id" label="Danh mục" placeholder="Chọn danh mục..." required>
                    @foreach($this->categories as $cat)
                        <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="price" type="number" label="Giá bán (VNĐ)" placeholder="VD: 150000" min="0" required />

                <flux:select wire:model="item_status" label="Tình trạng phục vụ" required>
                    @foreach(\App\Enums\MenuItemStatus::cases() as $statusEnum)
                        <flux:select.option value="{{ $statusEnum->value }}">{{ $statusEnum->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:textarea wire:model="description" label="Mô tả món ăn" placeholder="Thành phần, hương vị, lưu ý dị ứng..." rows="3" />

            <flux:input wire:model="image" type="file" label="Hình ảnh minh họa" accept="image/*" />

            @if ($image)
                <div class="mt-2">
                    <span class="text-sm text-zinc-500 mb-1 block">Bản xem trước:</span>
                    <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-lg border border-zinc-200 shadow-sm">
                </div>
            @endif

            <div class="flex justify-end gap-2 pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Lưu món ăn</flux:button>
            </div>
        </form>
    </flux:modal>
</div>

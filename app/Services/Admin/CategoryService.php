<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Services\Core\ImageUploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        protected ImageUploadService $imageService
    ) {}

    public function getList($search = ''): Collection
    {
        return Category::query()
                       ->when($search, function ($query, $search) {
                           $query->where('name', 'like', '%' . $search . '%');
                       })
                       ->orderBy('sort_order')
                       ->get();
    }

    public function store(array $data, $thumbnailFile = null): Category
    {
        $thumbnailPath = null;

        // Nếu không truyền sort_order (hoặc rỗng), tự động xếp cuối cùng (Max + 1)
        $sortOrder = isset($data['sort_order']) && $data['sort_order'] !== ''
            ? (int) $data['sort_order']
            : (Category::max('sort_order') ?? -1) + 1;

        // Xử lý dời thứ tự nếu vị trí này đã có danh mục đứng
        $this->shiftSortOrderIfNeeded($sortOrder);

        if ($thumbnailFile) {
            $thumbnailPath = $this->imageService->handleUpload(
                file: $thumbnailFile,
                path: Category::THUMBNAIL_PATH,
                cropSize: ['width' => 500, 'height' => 500],
                quality: 85
            );
        }

        return Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'sort_order' => $sortOrder,
            'thumbnail' => $thumbnailPath,
        ]);
    }

    public function update(int $id, array $data, $thumbnailFile = null)
    {
        $category = Category::findOrFail($id);
        $thumbnailPath = $category->thumbnail;

        // Nếu không truyền order, giữ nguyên order cũ của danh mục
        $sortOrder = isset($data['sort_order']) && $data['sort_order'] !== ''
            ? (int) $data['sort_order']
            : $category->sort_order;

        // Chỉ kiểm tra và dời thứ tự nếu Order bị thay đổi
        if ($category->sort_order !== $sortOrder) {
            $this->shiftSortOrderIfNeeded($sortOrder, $category->id);
        }

        if ($thumbnailFile) {
            // Xóa ảnh cũ trước khi lưu ảnh mới
            if ($category->thumbnail && Storage::disk('public')->exists(Category::THUMBNAIL_PATH . $category->thumbnail)) {
                Storage::disk('public')->delete(Category::THUMBNAIL_PATH . $category->thumbnail);
            }

            $thumbnailPath = $this->imageService->handleUpload(
                file: $thumbnailFile,
                path: Category::THUMBNAIL_PATH,
                cropSize: ['width' => 500, 'height' => 500],
                quality: 85
            );
        }

        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'sort_order' => $sortOrder,
            'thumbnail' => $thumbnailPath,
        ]);

        return $category;
    }

    public function destroy(int $id): bool
    {
        $category = Category::findOrFail($id);

        // Lưu lại vị trí trước khi xóa
        $deletedOrder = $category->sort_order;

        if ($category->thumbnail) {
            $this->imageService->deleteImage(Category::THUMBNAIL_PATH . $category->thumbnail);
        }
        // Xóa dữ liệu trong Database
        $category->delete();

        // Re-index
        Category::where('sort_order', '>', $deletedOrder)->decrement('sort_order');

        return true;
    }

    private function shiftSortOrderIfNeeded(int $targetOrder, ?int $excludeId = null): void
    {
        $conflict = Category::where('sort_order', $targetOrder)
                            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                            ->exists();

        if ($conflict) {
            Category::where('sort_order', '>=', $targetOrder)
                    ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                    ->increment('sort_order');
        }
    }
}

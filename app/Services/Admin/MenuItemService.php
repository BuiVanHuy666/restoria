<?php

namespace App\Services\Admin;

use App\Models\MenuItem;
use App\Services\Core\ImageUploadService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class MenuItemService
{
    public function __construct(
        protected ImageUploadService $imageService,
    ) {}

    public function getList(string $search = '', ?int $categoryId = null, ?string $status = null): LengthAwarePaginator
    {
        return MenuItem::with('categories')
                       ->filter([
                           'search' => $search,
                           'category_id' => $categoryId,
                           'status' => $status,
                       ])
                       ->latest()
                       ->paginate(10);
    }

    public function store(array $data, $imageFile = null): MenuItem
    {
        $imagePath = null;
        if ($imageFile) {
            $imagePath = $this->imageService->handleUpload(
                file: $imageFile,
                path: MenuItem::IMAGE_PATH,
                cropSize: ['width' => 800, 'height' => 800],
                quality: 100
            );
        }

        $menuItem = MenuItem::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'status' => $data['status'],
            'image' => $imagePath,
            'is_new' => $data['is_new'] ?? false,
            'is_popular' => $data['is_popular'] ?? false,
            'is_round_image' => $data['is_round_image'] ?? false
        ]);

        $menuItem->categories()->sync($data['category_ids']);

        return $menuItem;
    }

    public function update(int $id, array $data, $imageFile = null): MenuItem
    {
        $menuItem = MenuItem::findOrFail($id);
        $imagePath = $menuItem->image;

        if ($imageFile) {
            if ($menuItem->image) {
                $this->imageService->deleteImage(MenuItem::IMAGE_PATH . $menuItem->image);
            }
            $imagePath = $this->imageService->handleUpload(
                file: $imageFile,
                path: MenuItem::IMAGE_PATH,
                cropSize: ['width' => 800, 'height' => 800],
                quality: 100
            );
        }

        $menuItem->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'status' => $data['status'],
            'image' => $imagePath,
            'is_new' => $data['is_new'] ?? false,
            'is_popular' => $data['is_popular'] ?? false,
            'is_round_image' => $data['is_round_image'] ?? false
        ]);

        $menuItem->categories()->sync($data['category_ids']);

        return $menuItem;
    }

    public function destroy(int $id): bool
    {
        $menuItem = MenuItem::findOrFail($id);

        if ($menuItem->image) {
            $this->imageService->deleteImage(MenuItem::IMAGE_PATH . $menuItem->image);
        }

        $menuItem->delete();

        return true;
    }
}

<?php

namespace App\Services\Customer;

use App\Models\User;
use Exception;

class AddressService {
    public function saveAddress(User $user, array $validatedData, bool $isEditMode, ?int $addressId = null): string
    {
        if (!empty($validatedData['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }

        if ($isEditMode && $addressId) {
            $address = $user->addresses()->findOrFail($addressId);
            $address->update($validatedData);
            return 'Đã cập nhật địa chỉ thành công!';
        }

        if ($user->addresses()->count() === 0) {
            $validatedData['is_default'] = true;
        }

        $user->addresses()->create($validatedData);
        return 'Đã thêm địa chỉ mới!';
    }

    public function setAsDefault(User $user, int $addressId): void
    {
        $user->addresses()->update(['is_default' => false]);
        $user->addresses()->where('id', $addressId)->update(['is_default' => true]);
    }

    public function deleteAddress(User $user, int $addressId): void
    {
        $address = $user->addresses()->findOrFail($addressId);

        if ($address->is_default) {
            throw new Exception('Không thể xóa địa chỉ mặc định. Vui lòng chọn địa chỉ khác làm mặc định trước.');
        }

        $address->delete();
    }
}

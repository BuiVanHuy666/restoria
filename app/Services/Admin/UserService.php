<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

class UserService
{
    public function getList(string $search = '', string $role = '', string $status = ''): LengthAwarePaginator
    {
        return User::query()
                   ->filter([
                       'search' => $search,
                       'role'   => $role,
                       'status' => $status
                   ])
                   ->latest()
                   ->paginate(10);
    }

    public function toggleStatus(int $userId): bool
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            throw new Exception('Bạn không thể tự khóa tài khoản quản trị viên!');
        }

        return $user->update([
            'is_active' => !$user->is_active
        ]);
    }

    public function updateRole(int $userId, string $newRole): bool
    {
        $user = User::findOrFail($userId);

        return $user->update(['role' => $newRole]);
    }

    public function destroy(int $userId): void
    {
        DB::transaction(function () use ($userId) {
            $user = User::findOrFail($userId);

            if ($user->id === auth()->id()) {
                throw new Exception('Không thể xóa tài khoản đang đăng nhập!');
            }

            $user->addresses()->delete();
            $user->delete();
        });
    }
}

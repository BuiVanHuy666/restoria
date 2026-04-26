<?php

use App\Services\Admin\UserService;
use App\Models\User;
use App\Services\Core\LocationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Flux\Flux;

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý người dùng',
    'heading' => 'Tài khoản hệ thống',
    'subheading' => 'Quản lý thông tin khách hàng, nhân viên và phân quyền truy cập.'
])]
class extends Component {
    use WithPagination;

    public string $search = '';
    public string $role = '';
    public string $status = '';
    public string|int|null $deleteId = null;
    public ?int $selectedUserId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $user_role = 'customer';
    public bool $is_active = true;

    public bool $isEditMode = false;
    public ?int $editId = null;

    protected UserService $userService;

    public function boot(
        UserService $userService,
        LocationService $locationService
    ): void {
        $this->userService = $userService;
        $this->locationService = $locationService;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$this->editId,
            'password' => $this->isEditMode ? 'nullable|min:8' : 'required|min:8',
            'user_role' => 'required|in:admin,customer',
        ];
    }

    #[Computed]
    public function users()
    {
        return $this->userService->getList($this->search, $this->role, $this->status);
    }

    #[Computed]
    public function selectedUser(): ?User
    {
        if (!$this->selectedUserId) return null;
        return User::with('addresses')->find($this->selectedUserId);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function toggleStatus($id): void
    {
        $this->userService->toggleStatus($id);
        Flux::toast('Đã cập nhật trạng thái người dùng.', variant: 'success');
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        Flux::modal('confirm-delete-user')->show();
    }

    public function showDetail($id): void
    {
        unset($this->selectedUser);
        $this->selectedUserId = $id;

        Flux::modal('user-detail-modal')->show();
    }

    public function edit($id): void
    {
        $this->resetValidation();
        $item = \App\Models\User::with('addresses')->findOrFail(id: $id);

        $this->editId = $item->id;
        $this->name = $item->name;
        $this->email = $item->email;
        $this->user_role = $item->role;
        $this->is_active = $item->is_active;
        $this->isEditMode = true;

        Flux::modal('user-form-modal')->show();
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'user_role', 'is_active', 'isEditMode', 'editId']);
        $this->user_role = 'customer';
        $this->is_active = true;

        Flux::modal('user-form-modal')->show();
    }

    public function save(): void
    {
        $this->validate();

        try {
            if ($this->isEditMode) {
                $user = User::findOrFail($this->editId);
                $data = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'role' => $this->user_role,
                    'is_active' => $this->is_active,
                ];
                if ($this->password) $data['password'] = Hash::make($this->password);
                $user->update($data);
                Flux::toast('Đã cập nhật thông tin thành công.', variant: 'success');
            } else {
                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($this->password),
                    'role' => $this->user_role,
                    'is_active' => $this->is_active,
                ]);
                Flux::toast('Đã tạo tài khoản mới thành công.', variant: 'success');
            }

            Flux::modal('user-form-modal')->close();
        } catch (\Exception $e) {
            Flux::toast('Có lỗi xảy ra: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function destroy(): void
    {
        if ($this->deleteId) {
            $this->userService->destroy($this->deleteId);
            Flux::toast('Đã xóa người dùng thành công.', variant: 'success');
            Flux::modal('confirm-delete-user')->close();
        }
    }
}
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
        <div class="flex flex-1 gap-4 w-full">
            <flux:input wire:model.live.debounce.300ms="search" view="search" placeholder="Tìm tên, email..." class="max-w-xs"/>

            <flux:select wire:model.live="role" placeholder="Vai trò" class="max-w-45">
                <flux:select.option value="">Tất cả vai trò</flux:select.option>
                <flux:select.option value="admin">Quản trị viên</flux:select.option>
                <flux:select.option value="customer">Khách hàng</flux:select.option>
            </flux:select>

            <flux:select wire:model.live="status" placeholder="Trạng thái" class="max-w-45">
                <flux:select.option value="">Tất cả trạng thái</flux:select.option>
                <flux:select.option value="active">Đang hoạt động</flux:select.option>
                <flux:select.option value="banned">Bị khóa</flux:select.option>
            </flux:select>
        </div>

        <flux:button variant="primary" icon="user-plus" wire:click="create">Thêm người dùng</flux:button>
    </div>

    <flux:card class="overflow-hidden">
        <flux:table :paginate="$this->users">
            <flux:table.columns>
                <flux:table.column>Người dùng</flux:table.column>
                <flux:table.column>Vai trò</flux:table.column>
                <flux:table.column>Trạng thái</flux:table.column>
                <flux:table.column>Ngày tham gia</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach($this->users as $user)
                    <flux:table.row :key="$user->id">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-white cursor-pointer hover:text-emerald-600"
                                         wire:click="showDetail({{ $user->id }})">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-xs text-zinc-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge :color="$user->role === 'admin' ? 'fuchsia' : 'zinc'" size="sm">
                                {{ $user->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge :color="$user->is_active ? 'emerald' : 'rose'" size="sm">
                                {{ $user->is_active ? 'Hoạt động' : 'Bị khóa' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-zinc-500 text-sm">
                            {{ $user->created_at->format('d/m/Y') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"/>
                                <flux:menu>
                                    <flux:menu.item icon="pencil">Sửa thông tin</flux:menu.item>
                                    <flux:menu.item icon="{{ $user->is_active ? 'lock-closed' : 'lock-open' }}"
                                                    wire:click="toggleStatus({{ $user->id }})">
                                        {{ $user->is_active ? 'Khóa tài khoản' : 'Mở khóa' }}
                                    </flux:menu.item>
                                    <flux:menu.separator/>
                                    <flux:menu.item icon="trash" variant="danger" wire:click="confirmDelete({{ $user->id }})">
                                        Xóa vĩnh viễn
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal name="user-form-modal" class="md:w-150">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $isEditMode ? 'Cập nhật tài khoản' : 'Thêm tài khoản mới' }}</flux:heading>
                <flux:subheading>Điền thông tin tài khoản để truy cập vào hệ thống.</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Họ và tên" placeholder="VD: Nguyễn Văn A" required/>
                <flux:input wire:model="email" type="email" label="Địa chỉ Email" placeholder="email@restoria.vn" required/>

                <flux:input wire:model="password" type="password" label="Mật khẩu" description="{{ $isEditMode ? 'Để trống nếu không muốn đổi mật khẩu.' : 'Tối thiểu 8 ký tự.' }}"/>

                <flux:select wire:model="user_role" label="Vai trò hệ thống">
                    <flux:select.option value="customer">Khách hàng</flux:select.option>
                    <flux:select.option value="admin">Quản trị viên</flux:select.option>
                </flux:select>
            </div>

            <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50 dark:bg-zinc-800/50">
                <flux:switch wire:model="is_active" label="Trạng thái hoạt động" description="Cho phép người dùng đăng nhập vào hệ thống."/>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Lưu thông tin</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="user-detail-modal" class="md:w-175">
        @if($this->selectedUser)
            <div class="space-y-6" wire:key="user-detail-{{ $this->selectedUser->id }}">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-4">
                        <div>
                            <flux:heading size="xl">{{ $this->selectedUser->name }}</flux:heading>
                            <div class="flex items-center gap-2 text-zinc-500 text-sm">
                                <span>ID: #{{ $this->selectedUser->id }}</span>
                                <flux:separator vertical variant="subtle" class="h-3"/>
                                <span>Gia nhập: {{ $this->selectedUser->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <flux:badge :color="$this->selectedUser->role === 'admin' ? 'fuchsia' : 'zinc'">
                            {{ $this->selectedUser->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}
                        </flux:badge>
                        <flux:badge :color="$this->selectedUser->is_active ? 'emerald' : 'rose'" size="sm">
                            {{ $this->selectedUser->is_active ? 'Đang hoạt động' : 'Đang bị khóa' }}
                        </flux:badge>
                    </div>
                </div>

                <flux:separator variant="subtle"/>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="text-xs text-zinc-500 uppercase font-bold tracking-wider">Thông tin liên hệ</div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-zinc-600 dark:text-zinc-400">
                                <flux:icon.envelope class="w-4 h-4"/>
                                <span>{{ $this->selectedUser->email }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-zinc-600 dark:text-zinc-400">
                                <flux:icon.phone class="w-4 h-4"/>
                                <span>{{ $this->selectedUser->phone_number ?? 'Chưa cập nhật số điện thoại' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50/50 dark:bg-zinc-800/30">
                        <div class="text-xs text-zinc-500 uppercase font-bold mb-3">Thao tác nhanh</div>
                        <flux:switch
                            wire:click="toggleStatus({{ $this->selectedUser->id }})"
                            :checked="$this->selectedUser->is_active"
                            label="Cho phép hoạt động"
                            description="Tắt để khóa tài khoản ngay lập tức"/>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="text-xs text-zinc-500 uppercase font-bold tracking-wider">Sổ địa chỉ
                        ({{ $this->selectedUser->addresses->count() }})
                    </div>

                    @forelse($this->selectedUser->addresses as $addr)
                        <div class="p-3 border {{ $addr->is_default ? 'border-emerald-200 dark:border-emerald-800' : 'border-zinc-200' }} rounded-lg flex justify-between items-start">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm">{{ $addr->receiver_name }}</span>
                                    <span class="text-zinc-400">|</span>
                                    <span class="text-sm">{{ $addr->receiver_phone_number }}</span>
                                    @if($addr->is_default)
                                        <flux:badge size="sm" color="emerald" inset="top bottom" class="text-[10px]">Mặc
                                            định
                                        </flux:badge>
                                    @endif
                                </div>
                                <div class="text-xs text-zinc-500 mt-1">
                                    {{ $addr->address_detail }},
                                    {{ $this->locationService->getWardName($addr->province_code, $addr->ward_code) }} ,
                                    {{ $this->locationService->getProvinceName($addr->province_code) }}
                                </div>
                            </div>
                            <flux:icon.map-pin class="w-4 h-4 text-zinc-300"/>
                        </div>
                    @empty
                        <div class="text-sm text-zinc-400 italic p-4 border border-dashed border-zinc-200 rounded-lg text-center">
                            Người dùng này chưa cập nhật địa chỉ giao hàng.
                        </div>
                    @endforelse
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <flux:modal.close>
                        <flux:button variant="ghost">Đóng</flux:button>
                    </flux:modal.close>
                    <flux:button variant="primary" icon="pencil" wire:click="edit({{ $this->selectedUser->id }})">
                        Chỉnh sửa tài khoản
                    </flux:button>
                </div>
            </div>
        @endif
    </flux:modal>

    <flux:modal name="confirm-delete-user" class="md:w-110">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Xóa người dùng?</flux:heading>
                <flux:subheading>Hành động này không thể hoàn tác. Mọi dữ liệu liên quan đến người dùng này sẽ bị ảnh
                    hưởng.
                </flux:subheading>
            </div>
            <div class="flex gap-2 justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Hủy</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" variant="danger">Xác nhận xóa</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

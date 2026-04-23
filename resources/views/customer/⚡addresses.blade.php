<?php

use App\Models\UserAddress;
use App\Services\Core\LocationService;
use App\Services\Customer\AddressService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Validation\Rule;
use SweetAlert2\Laravel\Traits\WithSweetAlert;

new #[Layout('components.layouts.dashboard', [
    'title' => 'Sổ địa chỉ',
    'header' => 'Địa chỉ của tôi',
    'description' => 'Quản lý các địa điểm giao hàng để Restoria phục vụ Quý khách nhanh chóng nhất.'
])]
class extends Component {
    use WithSweetAlert;

    public ?int $address_id = null;
    public string $receiver_name = '';
    public string $receiver_phone_number = '';
    public ?int $province_code = null;
    public ?int $ward_code = null;
    public string $address_detail = '';
    public bool $is_default = false;

    public bool $isEditMode = false;

    public function getProvincesProperty(LocationService $locationService): array
    {
        return $locationService->getProvinces();
    }

    public function getWardsProperty(LocationService $locationService): array
    {
        if (!$this->province_code) return [];
        return $locationService->getWards((int)$this->province_code);
    }

    public function updatedProvinceCode(): void
    {
        $this->ward_code = null;
    }

    public function getProvinceName($code): string
    {
        return app(LocationService::class)->getProvinceName((int)$code);
    }

    public function getWardName($provinceCode, $wardCode): string
    {
        return app(LocationService::class)->getWardName((int)$provinceCode, (int)$wardCode);
    }

    protected function rules(): array
    {
        return [
            'receiver_name' => ['required', 'string', 'max:255'],
            'receiver_phone_number' => ['required', 'string', 'regex:/(84|0[3|5|7|8|9])+([0-9]{8})\b/'],
            'province_code' => ['required', 'integer'],
            'ward_code' => ['required', 'integer'],
            'address_detail' => ['required', 'string', 'max:255'],
            'is_default' => ['boolean']
        ];
    }

    protected array $messages = [
        'receiver_name.required' => 'Vui lòng nhập tên người nhận.',
        'receiver_phone_number.required' => 'Vui lòng nhập số điện thoại.',
        'receiver_phone_number.regex' => 'Số điện thoại không hợp lệ.',
        'province_code.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
        'ward_code.required' => 'Vui lòng chọn Phường/Xã.',
        'address_detail.required' => 'Vui lòng nhập địa chỉ cụ thể.',
    ];

    public function getAddressesProperty()
    {
        return auth()->user()->addresses()->orderByDesc('is_default')->latest()->get();
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['address_id', 'province_code', 'ward_code', 'address_detail', 'is_default']);

        $user = auth()->user();
        $this->receiver_name = $user->name;
        $this->receiver_phone_number = $user->phone_number ?? '';
        $this->isEditMode = false;
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $address = auth()->user()->addresses()->findOrFail($id);

        $this->address_id = $address->id;
        $this->receiver_name = $address->receiver_name;
        $this->receiver_phone_number = $address->receiver_phone_number;
        $this->province_code = $address->province_code;
        $this->ward_code = $address->ward_code;
        $this->address_detail = $address->address_detail;
        $this->is_default = $address->is_default;

        $this->isEditMode = true;
    }

    public function saveAddress(AddressService $addressService): void
    {
        $validated = $this->validate();

        $message = $addressService->saveAddress(
            auth()->user(),
            $validated,
            $this->isEditMode,
            $this->address_id
        );

        $this->dispatch('close-modal');
        $this->swalSuccess(['title' => 'Thành công!', 'text' => $message]);
    }

    public function setAsDefault(int $id, AddressService $addressService): void
    {
        $addressService->setAsDefault(auth()->user(), $id);
        $this->swalSuccess(['title' => 'Hoàn tất!', 'text' => 'Đã thay đổi địa chỉ mặc định.']);
    }

    public function deleteAddress(int $id, AddressService $addressService): void
    {
        try {
            $addressService->deleteAddress(auth()->user(), $id);
            $this->swalSuccess(['title' => 'Đã xóa!', 'text' => 'Địa chỉ đã được xóa.']);
        } catch (\Exception $e) {
            $this->swalError(['title' => 'Lỗi!', 'text' => $e->getMessage()]);
        }
    }
};
?>

<div>
    <div class="mb-5 text-right">
        <button type="button" wire:click="create" data-toggle="restoria-modal" data-target="#addressModal" class="theme-btn btn-style-one btn-sm border-0">
            <span class="btn-wrap">
                <span class="text-one"><i class="fas fa-plus mr-2"></i> Thêm địa chỉ mới</span>
                <span class="text-two"><i class="fas fa-plus mr-2"></i> Thêm địa chỉ mới</span>
            </span>
        </button>
    </div>

    <div class="row">
        @forelse($this->addresses as $address)
            <div class="col-md-6 mb-4">
                <div class="address-card-glass {{ $address->is_default ? 'is-default' : '' }}">
                    @if($address->is_default)
                        <span class="default-badge">Mặc định</span>
                    @endif
                    <div class="address-name">{{ $address->receiver_name }}</div>
                    <span class="address-phone">{{ $address->receiver_phone_number }}</span>
                    <div class="address-detail">
                        {{ $address->address_detail }}
                        {{ $this->getWardName($address->province_code, $address->ward_code) }}
                        {{ $this->getProvinceName($address->province_code) }}
                    </div>
                    <div class="address-actions mt-3">
                        <button wire:click="edit({{ $address->id }})" data-toggle="restoria-modal" data-target="#addressModal" class="btn btn-sm text-white bg-transparent border-0 px-0 mr-3">
                            <i class="far fa-edit mr-1 text-warning"></i> Chỉnh sửa
                        </button>
                        <button wire:click="deleteAddress({{ $address->id }})" wire:confirm="Bạn có chắc chắn muốn xóa?" class="btn btn-sm text-danger bg-transparent border-0 px-0">
                            <i class="far fa-trash-alt mr-1"></i> Xóa
                        </button>
                        @if(!$address->is_default)
                            <button wire:click="setAsDefault({{ $address->id }})" class="btn btn-sm bg-transparent border-0 px-0" style="margin-left: auto; color: var(--main-color);">
                                Đặt làm mặc định
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="glass-widget-box" style="opacity: 0.7;">
                    <i class="fas fa-map-marker-alt fa-3x mb-3 text-secondary"></i>
                    <p class="text-white">Bạn chưa có địa chỉ nào.</p>
                </div>
            </div>
        @endforelse
    </div>

    @teleport('body')
    <div class="restoria-modal" id="addressModal" wire:ignore.self>
        <div class="restoria-modal-dialog">
            <div class="restoria-modal-content">
                <button type="button" class="restoria-modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>

                <div class="title-box centered mb-4" style="position: relative; z-index: 5;">
                    <div class="subtitle"><span>Sổ địa chỉ</span></div>
                    <h2 class="text-white">{{ $isEditMode ? 'Chỉnh Sửa Địa Chỉ' : 'Thêm Địa Chỉ Mới' }}</h2>
                </div>

                <div style="position: relative; z-index: 5;">
                    <form wire:submit="saveAddress">
                        <div class="row clearfix text-left">
                            <div class="form-group col-md-6 mb-3">
                                <label class="text-warning small mb-1">Người nhận</label>
                                <div class="field-inner @error('receiver_name') has-error @enderror">
                                    <input type="text" wire:model="receiver_name" wire:dirty.class="dirty-input" placeholder="Tên người nhận">
                                    @error('receiver_name') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label class="text-warning small mb-1">Số điện thoại</label>
                                <div class="field-inner @error('receiver_phone_number') has-error @enderror">
                                    <input type="tel" wire:model="receiver_phone_number" wire:dirty.class="dirty-input" placeholder="Số điện thoại">
                                    @error('receiver_phone_number')
                                    <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label class="text-warning small mb-1">Tỉnh/Thành phố</label>
                                <div class="field-inner @error('province_code') has-error @enderror">
                                    <select wire:model.live="province_code" wire:dirty.class="dirty-input" class="glass-select">
                                        <option value="">Chọn Tỉnh/Thành phố</option>
                                        @foreach($this->provinces as $province)
                                            <option value="{{ $province['code'] }}">{{ $province['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('province_code') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label class="text-warning small mb-1">Phường/Xã</label>
                                <div class="field-inner @error('ward_code') has-error @enderror">
                                    <select wire:model="ward_code" wire:dirty.class="dirty-input" class="glass-select" @disabled(!$province_code)>
                                        <option value="">Chọn Phường/Xã</option>
                                        @foreach($this->wards as $ward)
                                            <option value="{{ $ward['code'] }}">{{ $ward['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('ward_code') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group col-12 mb-3">
                                <label class="text-warning small mb-1">Địa chỉ chi tiết</label>
                                <div class="field-inner @error('address_detail') has-error @enderror">
                                    <input type="text" wire:model="address_detail" wire:dirty.class="dirty-input" placeholder="Số nhà, Tên đường...">
                                    @error('address_detail') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group col-12 mb-4 mt-2">
                                <label class="d-flex align-items-center text-white" style="cursor: pointer;">
                                    <input type="checkbox" wire:model="is_default" class="mr-2" style="width: 18px; height: 18px;">
                                    Đặt làm địa chỉ mặc định
                                </label>
                            </div>

                            <div class="col-12 text-left mb-3" style="min-height: 20px;">
                                <div wire:dirty class="text-warning" style="font-size: 13px;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Có thay đổi chưa lưu!
                                </div>
                            </div>

                            <div class="form-group col-12">
                                <button type="submit" class="theme-btn btn-style-one w-100 border-0 btn-save-address" disabled wire:dirty.attr.remove="disabled" wire:loading.attr="disabled">
                                    <span class="btn-wrap" wire:loading.remove>
                                        <span class="text-one">Lưu Địa Chỉ</span>
                                        <span class="text-two">Lưu Địa Chỉ</span>
                                    </span>
                                    <span wire:loading class="text-white"><i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endteleport
</div>
@script
<script>
    Livewire.on('close-modal', () => {
        console.log('Đã nhận lệnh đóng modal từ Livewire!');

        $('.restoria-modal').removeClass('show');

        $('body').css('overflow', '');

        $('.modal-backdrop').remove();
    });
</script>
@endscript


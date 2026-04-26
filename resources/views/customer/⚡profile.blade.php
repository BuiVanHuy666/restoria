<?php

use App\Services\Customer\UpdateProfileService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Validation\Rule;
use SweetAlert2\Laravel\Traits\WithSweetAlert;

new #[Layout('components.layouts.dashboard', [
    'title' => 'Tài khoản',
    'header' => 'Thông tin Tài Khoản',
    'description' => 'Quản lý thông tin hồ sơ và bảo mật để nhận những đặc quyền tốt nhất từ Restoria.'
])]
class extends Component {
    use WithSweetAlert;

    public string $name = '';
    public string $email = '';
    public ?string $phone_number = '';
    public string $current_password = '';

    public bool $isSocialAccount = false;

    public bool $requiresPasswordConfirmation = true;

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;

        $this->isSocialAccount = !empty($user->provider);
    }

    protected function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'regex:/(84|0[3|5|7|8|9])+([0-9]{8})\b/', Rule::unique('users', 'phone_number')->ignore(auth()->id())],
        ];

        if (!$this->isSocialAccount) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(auth()->id())];

            if ($this->requiresPasswordConfirmation) {
                $rules['current_password'] = ['required', 'current_password'];
            }
        }

        return $rules;
    }

    protected array $messages = [
        'name.required' => 'Vui lòng nhập họ tên.',
        'email.required' => 'Vui lòng nhập email.',
        'email.unique' => 'Email này đã được sử dụng.',
        'phone_number.unique' => 'Số điện thoại này đã được sử dụng.',
        'phone_number.regex' => 'Số điện thoại không đúng định dạng hợp lệ.',
        'current_password.required' => 'Vui lòng nhập mật khẩu để xác nhận thay đổi.',
        'current_password.current_password' => 'Mật khẩu xác nhận không chính xác.',
    ];

    public function updateProfile(UpdateProfileService $updateProfileService): void
    {
        $validated = $this->validate();

        if (isset($validated['current_password'])) {
            session(['auth.password_confirmed_at' => time()]);
        }

        unset($validated['current_password']);

        $result = $updateProfileService(auth()->user(), $validated);

        $this->reset('current_password');

        $this->dispatch('close-modal');

        if ($result['requires_verification'] ?? false) {
            session()->flash('success', $result['message']);
            $this->redirectRoute('verification.notice', navigate: true);
            return;
        }

        $this->swalSuccess([
            'title' => 'Thành công!',
            'text' => $result['message'],
        ]);
    }
};
?>

<div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="glass-widget-box">
                <h5 class="widget-title">Thông tin liên hệ</h5>
                <div class="widget-info-list">
                    <p><strong>Họ tên:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Điện thoại:</strong> {{ auth()->user()->phone_number ?? 'Chưa cập nhật' }}</p>
                </div>

                <button type="button" data-toggle="restoria-modal" data-target="#editProfileModal" class="theme-btn btn-style-one btn-sm mt-4 border-0">
                    <span class="btn-wrap">
                        <span class="text-one">Chỉnh sửa hồ sơ</span>
                        <span class="text-two">Chỉnh sửa hồ sơ</span>
                    </span>
                </button>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="glass-widget-box border-info">
                <h5 class="widget-title">Thống kê nhanh</h5>
                <div class="widget-info-list">
                    <p><strong>Đơn hàng chờ xử lý:</strong> <span class="badge badge-warning ml-2">2 đơn</span></p>
                    <p><strong>Điểm tích lũy:</strong> <span class="stat-highlight ml-2">150</span> điểm</p>
                </div>
            </div>
        </div>
    </div>

    @teleport('body')
    <div class="restoria-modal" id="editProfileModal" wire:ignore.self>
        <div class="restoria-modal-dialog">
            <div class="restoria-modal-content">
                <button type="button" class="restoria-modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>

                <div class="title-box centered mb-4" style="position: relative; z-index: 5;">
                    <div class="subtitle"><span>Thông tin cá nhân</span></div>
                    <h2 class="text-white">Chỉnh
                        Sửa Hồ Sơ</h2>
                </div>

                <div style="position: relative; z-index: 5;">
                    <form wire:submit="updateProfile">
                        <div class="row clearfix">

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 mb-3">
                                <div class="field-inner @error('name') has-error @enderror" style="text-align: left;">
                                    <input type="text" wire:model="name" wire:dirty.class="dirty-input" wire:loading.attr="disabled" placeholder="Họ và tên">
                                    @error('name') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 mb-3">
                                <div class="field-inner @error('email') has-error @enderror" style="text-align: left;">
                                    <input
                                        type="text"
                                        wire:model="email"
                                        @if($isSocialAccount) disabled style="opacity: 0.6; cursor: not-allowed;" @else wire:dirty.class="dirty-input" @endif
                                        wire:loading.attr="disabled"
                                        placeholder="Địa chỉ Email"
                                    >
                                    @error('email') <span class="error-message">{{ $message }}</span> @enderror

                                    @if($isSocialAccount)
                                        <span style="font-size: 12px; color: var(--color-three); display: block; margin-top: 5px;">
                        <i class="fab fa-google text-warning"></i> Email liên kết với Google không thể thay đổi.
                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 mb-3">
                                <div class="field-inner @error('phone_number') has-error @enderror" style="text-align: left;">
                                    <input type="tel" wire:model="phone_number" wire:dirty.class="dirty-input" wire:loading.attr="disabled" placeholder="Số điện thoại">
                                    @error('phone_number') <span class="error-message">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            @if(!$isSocialAccount && $requiresPasswordConfirmation)
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 mb-4 pt-3" style="border-top: 1px dashed rgba(255,255,255,0.1);">
                                    <div class="field-inner @error('current_password') has-error @enderror" style="text-align: left;">
                                        <label class="text-white mb-2" style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: var(--main-color) !important;">
                                            Xác nhận bảo mật <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" wire:model="current_password" wire:loading.attr="disabled" placeholder="Nhập mật khẩu hiện tại để lưu thay đổi">
                                        @error('current_password')
                                        <span class="error-message">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="col-12 text-left mb-3" style="min-height: 20px;">
                                <div wire:dirty class="text-warning" style="font-size: 13px;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Bạn có thay đổi chưa lưu!
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 mt-2">
                                <div class="field-inner">
                                    <button
                                        type="submit"
                                        class="theme-btn btn-style-one w-100 border-0 btn-save-profile"
                                        disabled
                                        wire:dirty.attr.remove="disabled"
                                        wire:loading.attr="disabled"
                                    >
                                        <span class="btn-wrap" wire:loading.remove>
                                            <span class="text-one">Lưu Thay Đổi</span>
                                            <span class="text-two">Lưu Thay Đổi</span>
                                        </span>
                                                                    <span wire:loading class="text-white">
                                            <i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...
                                        </span>
                                    </button>
                                </div>
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
        $('.restoria-modal').removeClass('show');

        $('body').css('overflow', '');

        $('.modal-backdrop').remove();
    });
</script>
@endscript

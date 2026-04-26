<?php

use App\Services\Auth\UpdatePasswordService;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Livewire\Attributes\Layout;
use Livewire\Component;
use SweetAlert2\Laravel\Traits\WithSweetAlert;

new #[Layout('components.layouts.dashboard', [
    'title' => 'Thay đổi mật khẩu'
])]
class extends Component {
    use WithSweetAlert;

    public bool $isSocialAccount = false;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    protected array $messages = [
        'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
        'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
        'password.required' => 'Vui lòng nhập mật khẩu mới.',
        'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
    ];

    public function mount(): void
    {
        $user = auth()->user();
        $this->isSocialAccount = !empty($user->provider);
    }

    public function updatePassword(UpdatePasswordService $updatePasswordService): void
    {
        $validated = $this->validate();

        try {
            $updatePasswordService(auth()->user(), $validated);

            $this->swalSuccess([
                'title' => 'Cập nhật hoàn tất',
                'text' => 'Mật khẩu của Quý khách đã được thay đổi an toàn.',
                'confirmButtonText' => 'Xác nhận'
            ]);
            Log::driver('authentication')->info('Cập nhật thành ');
        } catch (Exception $e) {
            Log::driver('authentication')->error('Lỗi cập nhật mật khẩu: '.$e->getMessage());
            $this->swalError([
                'title' => 'Cập nhật không thành công',
                'text' => 'Đã có lỗi xảy ra trong quá trình xử lý. Quý khách vui lòng thử lại sau ít phút.',
                'confirmButtonText' => 'Thử lại'
            ]);
        }

        $this->reset(['current_password', 'password', 'password_confirmation']);
    }

    public function sendResetLink(): void
    {
        $status = PasswordFacade::sendResetLink(
            ['email' => auth()->user()->email]
        );

        if ($status == PasswordFacade::RESET_LINK_SENT) {
            $this->dispatch('swal', [
                'title' => 'Đã gửi Email!',
                'text' => 'Chúng tôi đã gửi link đặt lại mật khẩu vào email của bạn. Vui lòng kiểm tra hộp thư!',
                'icon' => 'success'
            ]);
        } else {
            $this->dispatch('swal', [
                'title' => 'Lỗi!',
                'text' => 'Không thể gửi link lúc này. Vui lòng thử lại sau.',
                'icon' => 'error'
            ]);
        }
    }
};
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="glass-widget-box" style="border-left: 1px solid rgba(255, 255, 255, 0.1);">
            <h5 class="widget-title">Cập Nhật Mật Khẩu</h5>

            @if($isSocialAccount)
                <div class="alert mt-4" style="background: rgba(255,255,255,0.05); border: 1px dashed rgba(255,255,255,0.2); color: #ccc;">
                    <i class="fab fa-google mr-2 text-warning"></i>
                    Tài khoản của bạn được liên kết qua mạng xã hội. Việc quản lý mật khẩu sẽ được thực hiện trực tiếp
                    trên nền tảng đó.
                </div>
            @else
                <form wire:submit="updatePassword" class="mt-4">
                    <div class="row">

                        <div class="col-12">
                            <div class="glass-form-group">
                                <label class="glass-form-label">Mật khẩu hiện tại</label>
                                <input type="password" wire:model="current_password" wire:loading.attr="disabled" class="glass-input" placeholder="Nhập mật khẩu hiện tại của bạn">

                                @error('current_password')
                                <span class="text-danger mt-1 d-block"><small>{{ $message }}</small></span>

                                <a href="#" wire:click.prevent="sendResetLink" class="text-warning mt-2 d-inline-block" style="font-size: 13px; text-decoration: underline; transition: all 0.3s;">
                                    <i class="fas fa-key mr-1"></i> Quên mật khẩu hiện tại? Nhấn để nhận link đặt lại
                                </a>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="glass-form-group">
                                <label class="glass-form-label">Mật khẩu mới</label>
                                <input type="password" wire:model="password" wire:loading.attr="disabled" class="glass-input" placeholder="Nhập mật khẩu mới">
                                @error('password')
                                <span class="text-danger mt-1 d-block"><small>{{ $message }}</small></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="glass-form-group">
                                <label class="glass-form-label">Xác nhận mật khẩu</label>
                                <input type="password" wire:model="password_confirmation" wire:loading.attr="disabled" class="glass-input" placeholder="Nhập lại mật khẩu mới">
                            </div>
                        </div>
                    </div>

                    <div class="password-note mt-3 mb-4">
                        <i class="fas fa-info-circle mr-2 text-primary"></i>
                        <small style="color: var(--color-three);">Gợi ý: Mật khẩu nên có ít nhất 8 ký tự, bao gồm chữ
                            cái, chữ số và ký tự đặc biệt.</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="theme-btn btn-style-one border-0" wire:loading.attr="disabled" wire:target="updatePassword">
                            <span class="btn-wrap" wire:loading.remove wire:target="updatePassword">
                                <span class="text-one">Lưu thay đổi</span>
                                <span class="text-two">Lưu thay đổi</span>
                            </span>
                            <span wire:loading wire:target="updatePassword" class="text-white">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...
                            </span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

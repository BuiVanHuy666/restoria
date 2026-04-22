<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.dashboard')]
class extends Component {
    //
};
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="glass-widget-box" style="border-left: 1px solid rgba(255, 255, 255, 0.1);">
            <h5 class="widget-title">Cập Nhật Mật Khẩu</h5>

            <form action="#" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-12">
                        <div class="glass-form-group">
                            <label class="glass-form-label">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="glass-input" placeholder="••••••••">
                            @error('current_password')
                            <span class="text-danger mt-1 d-block"><small>{{ $message }}</small></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="glass-form-group">
                            <label class="glass-form-label">Mật khẩu mới</label>
                            <input type="password" name="password" class="glass-input" placeholder="••••••••">
                            @error('password')
                            <span class="text-danger mt-1 d-block"><small>{{ $message }}</small></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="glass-form-group">
                            <label class="glass-form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" class="glass-input" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="password-note">
                    <i class="fas fa-info-circle mr-2" style="color: var(--main-color);"></i>
                    Gợi ý: Mật khẩu nên có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt.
                </div>

                <div class="mt-4">
                    <button type="submit" class="theme-btn btn-style-one">
                            <span class="btn-wrap">
                                <span class="text-one">Lưu thay đổi</span>
                                <span class="text-two">Lưu thay đổi</span>
                            </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

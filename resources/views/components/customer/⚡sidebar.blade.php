<?php

use Livewire\Component;

new class extends Component {
    public function mount(): void
    {
        $title = 'Xác nhận đăng xuất';
        $text = "Quý khách có chắc chắn muốn rời khỏi hệ thống Restoria?";
        confirmDelete($title, $text);
    }
};
?>

<ul class="user-menu">
    @foreach(config('menu.customer') ?? [] as $item)
        <li>
            <a wire:navigate href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">
                {{ $item['label'] }}
            </a>
        </li>
    @endforeach
    <hr>
    <li>
        <a href="{{ route('logout') }}" class="logout-btn" data-confirm-delete="true">
            <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </a>
    </li>
</ul>

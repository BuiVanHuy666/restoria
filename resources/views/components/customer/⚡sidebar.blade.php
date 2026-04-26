<?php

use Livewire\Component;

new class extends Component {};
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
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </button>
        </form>
    </li>
</ul>

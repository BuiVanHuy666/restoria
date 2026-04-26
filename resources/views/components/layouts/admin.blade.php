<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-50 dark:bg-zinc-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <title>{{ isset($title) ? $title . ' - ' : '' }}Restoria | Quản trị</title>
    @fluxAppearance

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">

<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

    <flux:brand href="" logo="{{ asset('images/logo.png') }}" class="px-2"/>
    <flux:brand href="" logo="{{ asset('images/logo-admin-dark.svg') }}" name="Restoria Admin" class="px-2 hidden dark:flex"/>

    <flux:navlist variant="outline">
        @foreach(config('menu.admin') as $item)
            <flux:navlist.item icon="{{$item['icon']}}" wire:navigate wire:current href="{{ route($item['route']) }}">{{ $item['label'] }}</flux:navlist.item>
        @endforeach
        <flux:navlist.group expandable heading="Hệ thống" class="mt-4">
            <flux:navlist.item href="#">Nhân viên</flux:navlist.item>
            <flux:navlist.item href="#">Báo cáo doanh thu</flux:navlist.item>
            <flux:navlist.item href="#">Cài đặt chung</flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer/>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="cog-6-tooth" href="#">Tài khoản của tôi</flux:navlist.item>
        <flux:navlist.item icon="arrow-right-start-on-rectangle" href="#">Đăng xuất</flux:navlist.item>
    </flux:navlist>
    <div class="px-2 mb-4">
        <flux:button variant="subtle" square class="w-full justify-start" x-on:click="$flux.dark = ! $flux.dark">
            <flux:icon.moon class="dark:hidden"/>
            <flux:icon.sun class="hidden dark:block"/>

            <span class="ml-2 dark:hidden">Chế độ tối</span>
            <span class="ml-2 hidden dark:block">Chế độ sáng</span>
        </flux:button>
    </div>
</flux:sidebar>

<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-3"/>
    <flux:spacer/>

    <flux:button variant="subtle" square x-on:click="$flux.dark = ! $flux.dark">
        <flux:icon.moon class="dark:hidden"/>
        <flux:icon.sun class="hidden dark:block"/>
    </flux:button>

    <flux:dropdown>
        <flux:profile avatar="https://ui-avatars.com/api/?name=Admin"/>
        <flux:menu>
            <flux:menu.item icon="cog-6-tooth">Tài khoản</flux:menu.item>
            <flux:menu.item icon="arrow-right-start-on-rectangle">Đăng xuất</flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</flux:header>

<flux:main>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl" level="1">{{ $heading ?? 'Dashboard' }}</flux:heading>
            <flux:subheading>{{ $subheading ?? 'Chào mừng bạn quay lại hệ thống quản trị.' }}</flux:subheading>
        </div>

        @if(isset($actions))
            <div class="flex gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>

    <flux:separator variant="subtle" class="mb-6"/>

    {{ $slot }}

</flux:main>

@persist('toast')
<flux:toast/>
@endpersist

@livewireScripts
@fluxScripts
</body>
</html>

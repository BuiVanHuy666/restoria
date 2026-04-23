@props(['title' => 'Trang cá nhân', 'header' => '', 'description' => ''])

@push('styles')
    @livewireStyles
@endpush

@push('scripts')
    @livewireScripts
@endpush
<x-layouts.app :title="$title">
    <section class="reserve-section style-two reserve-page section-kt customer-dashboard-section">
        <div class="image-layer" style="background-image: url('{{ asset('images/background/image-10.jpg') }}');"></div>
        <div class="auto-container">
            <div class="outer-box customer-dashboard-outer">
                <div class="row clearfix">
                    <div class="sidebar-side col-lg-3 col-md-4 col-sm-12 col-xs-12 mb-4">
                        <aside class="sidebar customer-sidebar">
                            <div class="user-info">
                                <h4 class="user-name">{{ auth()->user()->name ?? 'Khách Hàng' }}</h4>
                            </div>

                            <div class="sidebar-widget">
                                <livewire:customer.sidebar/>
                            </div>
                        </aside>
                    </div>

                    <div class="content-side col-lg-9 col-md-8 col-sm-12 col-xs-12">
                        <div class="content-box customer-dashboard-content">

                            @if($header)
                                <div class="content-header">
                                    <h3>{{ $header }}</h3>
                                    @if($description)
                                        <p>{{ $description }}</p>
                                    @endif
                                </div>
                            @endif

                            <div class="dashboard-inner-content">
                                {{ $slot }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

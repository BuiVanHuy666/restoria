<x-layouts.app :title="$title">
    @push('styles')
        <style>
            .reserve-section.style-two.reserve-page {
                padding-top: 150px;
            }

            .reserve-section.style-two .reserv-col .inner {
                display: block !important;
                width: 100%;
                max-width: 100%;
            }

            .reserv-col.mx-auto {
                float: none;
                margin: 0 auto;
            }

            @media (max-width: 767px) {
                .reserve-section.style-two .reserv-col .inner {
                    padding: 40px 20px;
                }
            }
        </style>
    @endpush

    <section class="reserve-section style-two reserve-page section-kt">
        <div class="image-layer" style="background-image: url('{{ asset('images/background/image-10.jpg') }}');"></div>

        <div class="auto-container">
            <div class="outer-box">
                <div class="row clearfix">
                    <div class="reserv-col col-lg-8 col-md-10 col-sm-12 mx-auto">
                        <div class="inner">
                            <div class="title-box centered">
                                <div class="subtitle"><span>{{ $formSubtitle }}</span></div>
                                <h2>{{ $formTitle }}</h2>
                                @if(isset($requestInfo))
                                    <div class="request-info">{!! $requestInfo !!}</div>
                                @endif
                            </div>

                            <div class="default-form reservation-form">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 p-0">
                                    <div class="field-inner">
                                        <a href="{{ url('auth/google') }}" class="theme-btn btn-style-two btn-google clearfix w-100">
                                            <span class="btn-wrap">
                                                <span class="text-one"><i class="fab fa-google mr-2"></i> Đăng nhập bằng Google</span>
                                                <span class="text-two"><i class="fab fa-google mr-2"></i> Đăng nhập bằng Google</span>
                                            </span>
                                        </a>
                                        <div class="text-center text-white my-3"><small>— HOẶC —</small></div>
                                    </div>
                                </div>

                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

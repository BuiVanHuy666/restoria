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
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

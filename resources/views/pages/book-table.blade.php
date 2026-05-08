<x-layouts.app title="Đặt bàn">
    <section class="reserve-section style-two reserve-page section-kt">
        <div class="image-layer" style="background-image: url('{{ asset('images/background/image-10.jpg') }}');"></div>

        <div class="auto-container">
            <div class="outer-box">
                <div class="row clearfix">
                    <div class="reserv-col col-lg-8 col-md-12 col-sm-12 mx-auto">
                        <div class="inner">
                            <div class="title-box centered">
                                <div class="subtitle"><span>Đặt bàn trực tuyến</span></div>
                                <h2>Đặt Bàn Ngay</h2>
                                <div class="request-info">
                                    Yêu cầu đặt bàn qua <a href="tel:+88123123456">+88-123-123456</a> hoặc điền vào mẫu bên dưới
                                </div>
                            </div>

                            <div class="default-form reservation-form">
                                {{-- Trỏ action về route store --}}
                                <form action="{{ route('client.book-table.store') }}" method="POST">
                                    @csrf

                                    <div class="row clearfix">
                                        {{-- Họ và Tên --}}
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                            <div class="field-inner">
                                                <input type="text" name="customer_name" placeholder="Họ và Tên"
                                                       value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}"
                                                       @if(auth()->check() && auth()->user()->name)
                                                           readonly class="bg-zinc-100 cursor-not-allowed opacity-70"
                                                    @endif
                                                >
                                                @error('customer_name') <span class="text-rose-500 text-xs italic mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        {{-- Số Điện Thoại --}}
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                            <div class="field-inner">
                                                <input type="text" name="phone_number" placeholder="Số Điện Thoại"
                                                       value="{{ auth()->check() ? auth()->user()->phone_number : old('phone_number') }}"
                                                       @if(auth()->check() && auth()->user()->phone_number)
                                                           readonly class="bg-zinc-100 cursor-not-allowed opacity-70"
                                                    @endif
                                                >
                                                @error('phone_number') <span class="text-rose-500 text-xs italic mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        {{-- Số Người --}}
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <div class="field-inner">
                                                <span class="alt-icon far fa-user"></span>
                                                <select class="l-icon" name="guests">
                                                    @for ($i = 1; $i <= 7; $i++)
                                                        <option value="{{ $i }}" {{ old('guests') == $i ? 'selected' : '' }}>{{ $i }} Người</option>
                                                    @endfor
                                                    <option value="8" {{ old('guests') == 8 ? 'selected' : '' }}>Hơn 7 người</option>
                                                </select>
                                                <span class="arrow-icon far fa-angle-down"></span>
                                            </div>
                                        </div>

                                        {{-- Ngày Đặt --}}
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <div class="field-inner">
                                                <span class="alt-icon far fa-calendar"></span>

                                                <input class="l-icon datepicker" type="text" name="reservation_date" placeholder="Ngày-Tháng-Năm" value="{{ old('reservation_date') }}" required readonly>

                                                <span class="arrow-icon far fa-angle-down"></span>
                                            </div>
                                            @error('reservation_date')
                                            <span class="text-rose-500 text-xs italic mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Giờ Đặt --}}
                                        <div class="form-group col-lg-4 col-md-12 col-sm-12">
                                            <div class="field-inner">
                                                <span class="alt-icon far fa-clock"></span>
                                                <select class="l-icon" name="reservation_time">
                                                    @php
                                                        $times = ['08 : 00 sáng', '09 : 00 sáng', '10 : 00 sáng', '11 : 00 sáng', '12 : 00 trưa', '01 : 00 chiều', '02 : 00 chiều', '03 : 00 chiều', '04 : 00 chiều', '05 : 00 chiều', '06 : 00 tối', '07 : 00 tối', '08 : 00 tối', '09 : 00 tối'];
                                                    @endphp
                                                    @foreach ($times as $time)
                                                        <option value="{{ $time }}" {{ old('reservation_time') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="arrow-icon far fa-angle-down"></span>
                                            </div>
                                        </div>

                                        {{-- Lời nhắn --}}
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                            <div class="field-inner">
                                                <textarea name="message" placeholder="Lời nhắn hoặc yêu cầu đặc biệt...">{{ old('message') }}</textarea>
                                                @error('message') <span class="text-rose-500 text-xs italic mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        {{-- Nút Gửi --}}
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                            <div class="field-inner">
                                                <button type="submit" class="theme-btn btn-style-one clearfix">
                                                    <span class="btn-wrap">
                                                        <span class="text-one">Đặt bàn ngay</span>
                                                        <span class="text-two">Gửi yêu cầu</span>
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
            </div>
        </div>
    </section>
</x-layouts.app>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cấu hình lại Datepicker của theme (Thường là jQuery UI hoặc Bootstrap Datepicker)
        if (typeof jQuery !== 'undefined' && jQuery.fn.datepicker) {
            $('.datepicker').datepicker('destroy'); // Xóa cấu hình cũ của theme
            $('.datepicker').datepicker({
                dateFormat: 'dd/mm/yy', // Chuẩn Ngày/Tháng/Năm
                minDate: 0,             // Khóa không cho chọn ngày trong quá khứ
                firstDay: 1             // Bắt đầu tuần từ Thứ 2
            });
        }
    });
</script>

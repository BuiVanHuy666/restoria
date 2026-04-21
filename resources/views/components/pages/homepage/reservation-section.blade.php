<section class="fluid-section reserve-section splitscreen">
    <div class="outer-container">
        <div class="outer-box">
            <div class="row clearfix">
                <div class="reserv-col col-lg-6 col-md-12 col-sm-12">
                    <div class="inner">
                        <div class="title-box centered">
                            <div class="subtitle"><span>Đặt bàn trực tuyến</span></div>
                            <h2>Đặt Bàn Ngay</h2>
                            <div class="request-info">
                                Yêu cầu đặt bàn qua <a href="tel:+88123123456">+88-123-123456</a> hoặc điền vào mẫu bên dưới
                            </div>
                        </div>

                        <div class="default-form reservation-form">
                            <form method="post" action="#">
                                <div class="row clearfix">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                        <div class="field-inner">
                                            <input type="text" name="customer_name" placeholder="Họ và Tên" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                        <div class="field-inner">
                                            <input type="text" name="phone_number" placeholder="Số Điện Thoại" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                        <div class="field-inner">
                                            <span class="alt-icon far fa-user"></span>
                                            <select class="l-icon" name="guests">
                                                <option value="1">1 Người</option>
                                                <option value="2">2 Người</option>
                                                <option value="3">3 Người</option>
                                                <option value="4">4 Người</option>
                                                <option value="5">5 Người</option>
                                                <option value="6">6 Người</option>
                                                <option value="7">7 Người</option>
                                            </select>
                                            <span class="arrow-icon far fa-angle-down"></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                        <div class="field-inner">
                                            <span class="alt-icon far fa-calendar"></span>
                                            <input class="l-icon datepicker" type="text" name="date" placeholder="Ngày-Tháng-Năm" required readonly>
                                            <span class="arrow-icon far fa-angle-down"></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-12 col-sm-12">
                                        <div class="field-inner">
                                            <span class="alt-icon far fa-clock"></span>
                                            <select class="l-icon" name="time">
                                                <option>08 : 00 sáng</option>
                                                <option>09 : 00 sáng</option>
                                                <option>10 : 00 sáng</option>
                                                <option>11 : 00 sáng</option>
                                                <option>12 : 00 trưa</option>
                                                <option>01 : 00 chiều</option>
                                                <option>02 : 00 chiều</option>
                                                <option>03 : 00 chiều</option>
                                                <option>04 : 00 chiều</option>
                                                <option>05 : 00 chiều</option>
                                                <option>06 : 00 tối</option>
                                                <option>07 : 00 tối</option>
                                                <option>08 : 00 tối</option>
                                                <option>09 : 00 tối</option>
                                                <option>10 : 00 tối</option>
                                            </select>
                                            <span class="arrow-icon far fa-angle-down"></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <div class="field-inner">
                                            <textarea name="message" placeholder="Lời nhắn hoặc yêu cầu đặc biệt..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <div class="field-inner">
                                            <button type="submit" class="theme-btn btn-style-one clearfix">
                                                <span class="btn-wrap">
                                                    <span class="text-one">Đặt bàn ngay</span>
                                                    <span class="text-two">Đặt bàn ngay</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="graphic-col col-lg-6 col-md-12 col-sm-12">
                    <div class="inner">
                        <div class="content-box">
                            <div class="video-image">
                                <img src="{{ asset('images/resource/split-sec-img.jpg') }}" alt="Hình ảnh nhà hàng">
                                <div class="play-btn">
                                    <a href="https://www.youtube.com/watch?v=dZ9O_l1dIzs" class="lightbox-image theme-btn">
                                        <span class="icon fal fa-play"><i class="ripple"></i></span>
                                    </a>
                                </div>
                            </div>

                            <div class="desc">
                                <h5> Ghé thăm chúng tôi </h5>
                                <div class="text">
                                    Restoria, Quận 1, TP. Hồ Chí Minh <br> <br>
                                    <span class="info-ttl"> Giờ Ăn Trưa </span> - 10:00 sáng – 3:30 chiều <br>
                                    <span class="info-ttl dinner"> Giờ Ăn Tối </span> - 08:00 tối – 10:30 tối
                                </div>
                            </div>
                        </div>

                        <div class="graphic-layer" style="background-image: url('{{ asset('images/background/reservation-bg.jpg') }}');"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

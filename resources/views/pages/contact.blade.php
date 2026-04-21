<x-layouts.app title="Liên hệ">
    <section class="inner-banner">
        <div class="image-layer" style="background-image: url({{ asset('images/background/banner-image-4.jpg') }});"></div>
        <div class="auto-container">
            <div class="inner">
                <h1>Liên Hệ</h1>
                <div class="sub_text">
                    <p> Hương vị hoàn hảo trong từng món ăn - <span class="primary-color"> ẩm thực cao cấp mang hơi thở hiện đại.</span></p>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-page section-kt">

        <div class="auto-container">
            <div class="c-page-form-box">
                <div class="row clearfix">

                    <div class="loc-block info-block col-lg-5 col-md-12 col-sm-12">
                        <div class="contact-image">
                            <img src="{{ asset('images/resource/restaurant.jpg') }}" alt="Nhà hàng Restoria">
                        </div>
                        <h5> Ghé thăm chúng tôi </h5>
                        <div class="text">
                            Restoria, Quận 1, TP. Hồ Chí Minh <br> <br>
                            <span class="c-info-ttl"> Giờ Ăn Trưa </span> - 10:00 sáng – 3:30 chiều <br>
                            <span class="c-info-ttl"> Giờ Ăn Tối </span> - 08:00 tối – 10:30 tối <br>  <br>
                            <span class="more-link"> Đặt bàn : <a href="tel:+85123456789">+85-123-456789</a> </span> <br>
                            <span class="more-link"> Email: <a href="mailto:booking@gmail.com">booking@gmail.com</a></span>
                        </div>
                    </div>

                    <div class="col-12 col-md-1 d-flex justify-content-center middle-line-wrapper">
                        <div class="middle-line-container">
                            <div class="dot top-dot"></div>
                            <div class="v-line"></div>
                            <div class="dot bottom-dot"></div>
                        </div>
                    </div>

                    <div class="loc-block col-lg-6 col-md-12 col-sm-12">
                        <div class="form-side">
                            <div class="title-box centered">
                                <div class="subtitle"><span>Kết nối với chúng tôi</span></div>
                                <h2>Gửi Lời Nhắn</h2>
                                <div class="text desc">Liên hệ với chúng tôi - chúng tôi sẽ phản hồi trong vòng 24 giờ và rất sẵn lòng hỗ trợ bạn!</div>
                            </div>
                            <div class="default-form reservation-form">
                                <form method="post" action="#">
                                    @csrf
                                    <div class="clearfix">
                                        <div class="form-group">
                                            <div class="field-inner">
                                                <input type="text" name="name" placeholder="Họ và Tên" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="field-inner">
                                                <input type="email" name="email" placeholder="Địa chỉ Email" required>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="field-inner">
                                                <input type="text" name="phone" placeholder="Số Điện Thoại" required>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <div class="field-inner">
                                                <textarea name="message" placeholder="Yêu cầu đặc biệt hoặc tin nhắn của bạn..." required></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="theme-btn btn-style-one clearfix">
                                            <span class="btn-wrap">
                                                <span class="text-one">Gửi tin nhắn ngay</span>
                                                <span class="text-two">Gửi tin nhắn ngay</span>
                                            </span>
                                            </button>
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

    <section class="contact-map">
        <div class="row clearfix">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4602324283407!2d106.6923812758384!3d10.77141505919934!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f3f10137233%3A0xc3f8e5695627250c!2zRGluaCDEkOG7mWMgTOG6rXA!5e0!3m2!1svi!2svn!4v1713670000000!5m2!1svi!2svn"
                    width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>
</x-layouts.app>

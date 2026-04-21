<footer class="main-footer">
    <div class="upper-section section-kt">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="footer-col info-col col-lg-6 col-md-12 col-sm-12">
                    <div class="inner wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
                        <div class="content">
                            <div class="logo">
                                <a href="{{ url('/') }}" title="Restoria">
                                    <img src="{{ asset('images/logo.png') }}" alt="Restoria Logo" title="Nhà hàng Restoria">
                                </a>
                            </div>
                            <div class="info">
                                <h6>Ghé thăm chúng tôi</h6>
                                <ul>
                                    <li>Địa chỉ: Restoria, Quận 1, TP. Hồ Chí Minh</li>
                                    <li>Giờ mở cửa: Hàng ngày - 8:00 sáng đến 10:00 tối</li>
                                    <li>Email: <a href="mailto:booking@gmail.com">booking@gmail.com</a></li>
                                    <li>Điện thoại: <a href="tel:+88123123456">Yêu cầu đặt bàn: +88-123-123456</a></li>
                                </ul>
                            </div>
                            <div class="separator"><span> </span></div>
                            <div class="newsletter">
                                <h4>Đăng ký nhận tin</h4>
                                <div class="text">Đăng ký ngay để nhận ưu đãi 25% và các cập nhật mới nhất từ chúng tôi.</div>
                                <div class="newsletter-form">
                                    <form method="post" action="#">
                                        @csrf {{-- Đừng quên directive này để tránh lỗi 419 khi submit form trong Laravel --}}
                                        <div class="form-group">
                                            <span class="alt-icon far fa-envelope"></span>
                                            <input type="email" name="email" value="" placeholder="Nhập email của bạn" required>
                                            <button type="submit" class="theme-btn btn-style-one clearfix">
                                                <span class="btn-wrap">
                                                    <span class="text-one">đăng ký</span>
                                                    <span class="text-two">đăng ký</span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-col footer-image-col col-lg-3 col-md-6 col-sm-12">
                    <div class="footer-image">
                        <img src="{{ asset('images/resource/footer-img-1.jpg') }}" alt="Không gian nhà hàng">
                    </div>
                </div>
                <div class="footer-col footer-image-col last col-lg-3 col-md-6 col-sm-12">
                    <div class="footer-image">
                        <img src="{{ asset('images/resource/footer-img-2.jpg') }}" alt="Món ăn Restoria">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom_footer">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <p>© 2026 Bản quyền thuộc về Restoria. Bảo lưu mọi quyền.</p>
                </div>

                <div class="col-md-6 col-sm-12">
                    <ul class="social_media">
                        <li>
                            <a href="#" aria-label="facebook page"><i class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li>
                            <a href="#" aria-label="instagram page"><i class="fa-brands fa-instagram"></i></a>
                        </li>
                        <li>
                            <a href="#" aria-label="pinterest page"><i class="fa-brands fa-pinterest-p"></i></a>
                        </li>
                        <li>
                            <a href="#" aria-label="youtube page"><i class="fa-brands fa-youtube"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

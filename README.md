# 🍽️ Restoria - My Restaurant Side Project

Chào mừng bạn đến với Restoria! Đây là dự án cá nhân do tôi xây dựng trong quá trình tự học và nghiên cứu về phát triển Website, đặc biệt là lập trình Back-end. Dự án này là nơi tôi thực hành sử dụng hệ sinh thái Laravel, học cách cấu trúc thư mục rõ ràng và từng bước làm quen với nghệ thuật viết code SOLID.

## 🎯 Mục tiêu dự án

* **Thực hành Laravel & Livewire:** Tạo ra trải nghiệm UI/UX mượt mà, không cần reload trang.
* **Học cách viết Code sạch:** Tổ chức thư mục rõ ràng, tách biệt logic thông qua Form Request, Enums và Model Casting.
* **Xử lý luồng nghiệp vụ thực tế:** Quản lý menu, giỏ hàng, và tích hợp thanh toán trực tuyến.

## 🛠️ Stack công nghệ đã sử dụng

* **Core:** PHP 8.3 & Laravel 13.
* **Front-end:** Livewire 4.2, Tailwind CSS 4.0 & Flux UI.
* **Database:** MySQL.
* **Security & Auth:** Laravel Socialite (Google Login).
* **Xử lý hình ảnh:** Intervention Image v4 (Tối ưu hóa ảnh WebP).
* **Thanh toán:** Tích hợp cổng VNPay.
* **Thông báo:** SweetAlert2 (`sweetalert2/laravel`).

## ✨ Các tính năng đã hoàn thiện

### 🔐 Xác thực & Quản lý người dùng (Authentication)
Dự án được xây dựng một luồng xác thực bảo mật và toàn diện:
* **Xác thực cơ bản:** Đầy đủ các tính năng Đăng ký, Đăng nhập và Đăng xuất.
* **Quên mật khẩu:** Hỗ trợ gửi link xác nhận qua email để đặt lại mật khẩu mới an toàn.
* **Xác thực Email:** Yêu cầu người dùng xác nhận địa chỉ email (Email Verification) để bảo vệ tài khoản.
* **Social Auth:** Đăng nhập nhanh bằng mạng xã hội (Google) thông qua Laravel Socialite.
* **Bảo toàn dữ liệu:** Áp dụng Soft Deletes (xóa mềm) và cờ trạng thái `is_active` để quản lý người dùng mà không làm mất dữ liệu lịch sử.

### 🛒 Dành cho khách hàng

* **Đặt bàn trực tuyến:** Form đặt bàn với tính năng tự động điền (Auto-fill) thông tin (tên, số điện thoại) nếu khách hàng đã đăng nhập.
* **Đặt món Online:** Duyệt menu món ăn và quản lý giỏ hàng.
* **Thanh toán VNPay:** Luồng thanh toán an toàn, trả kết quả thông qua Controller và hiển thị thông báo bằng SweetAlert2.

### 🛠️ Hệ thống quản trị (Admin Dashboard)

* **Quản lý đặt bàn:** Xem danh sách khách hàng, cập nhật trạng thái (Pending, Confirmed, Completed, Cancelled) bằng đối tượng **Enum** chuyên nghiệp.
* **Search & Filter:** Tìm kiếm khách hàng theo tên, số điện thoại và lọc theo trạng thái ngay lập tức (Real-time).
* **Quản lý Menu:** CRUD món ăn và danh mục thực đơn.

---
*Dự án này vẫn đang trong quá trình nâng cấp. Mình sẽ tiếp tục cập nhật thêm một số tính năng như Cache, gửi mail khi đặt bàn và đặt món, ... mới vào code trong tương lai!*

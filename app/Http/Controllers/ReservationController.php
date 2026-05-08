<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Enums\ReservationStatus;
use App\Http\Requests\ReservationRequest;
use Carbon\Carbon;
use SweetAlert2\Laravel\Swal;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        try {
            $data = $request->validated();

            $data['reservation_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['reservation_date'])->format('Y-m-d');

            $data['status'] = ReservationStatus::PENDING;

            Reservation::create($data);

            // Hiện Swal thành công
            Swal::success([
                'title' => 'Thành công!',
                'text' => 'Đặt bàn thành công. Chúng tôi sẽ liên hệ sớm!'
            ]);

            return back();

        } catch (\Exception $e) {
            Swal::error([
                'title' => 'Có lỗi xảy ra',
                'text' => 'Không thể gửi yêu cầu lúc này. Lỗi: ' . $e->getMessage()
            ]);
            return back()->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;
use App\Services\Payment\VNPayService;
use SweetAlert2\Laravel\Swal;

class PaymentController extends Controller
{
    public function vnpayReturn(Request $request, VNPayService $vnpayService, CartService $cartService)
    {
        $inputData = $request->all();
        $isValid = $vnpayService->verifySignature($inputData);

        if ($isValid) {
            $orderCode = $request->vnp_TxnRef;
            $order = Order::where('code', $orderCode)->first();

            if (!$order) {
                Swal::error([
                    'title' => 'Lỗi!',
                    'text' => 'Không tìm thấy đơn hàng.'
                ]);
                return redirect()->route('customer.order');
            }

            $order->update([
                'payment_detail' => $inputData,
            ]);

            if ($request->vnp_ResponseCode == '00') {
                $order->update([
                    'payment_status' => PaymentStatus::PAID,
                    'status' => OrderStatus::CONFIRMED,
                    'paid_at' => now(),
                ]);

                $cartService->clear();

                Swal::success([
                    'title' => 'Thành công!',
                    'text' => 'Thanh toán đơn hàng ' . $orderCode . ' hoàn tất.'
                ]);
                return redirect()->route('customer.order');
            } else {
                $order->update(['status' => OrderStatus::CANCELED]);

                Swal::error([
                    'title' => 'Thanh toán thất bại',
                    'text' => 'Giao dịch không thành công hoặc đã bị hủy.'
                ]);
                return redirect()->route('customer.order');
            }
        }

        Swal::warning([
            'title' => 'Cảnh báo',
            'text' => 'Chữ ký thanh toán không hợp lệ.'
        ]);
        return redirect()->route('customer.order');
    }
}

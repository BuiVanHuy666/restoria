<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:15',
            'guests' => 'required|integer|min:1',
            'reservation_date' => 'required|date_format:d/m/Y|after_or_equal:today',
            'reservation_time' => 'required|string',
            'message' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Vui lòng nhập họ và tên.',
            'phone_number.required' => 'Vui lòng nhập số điện thoại để chúng tôi liên hệ.',
            'reservation_date.required' => 'Vui lòng chọn ngày đặt bàn.',
            'reservation_date.date_format' => 'Ngày đặt bàn không đúng định dạng (dd/mm/yyyy).',
            'reservation_date.after_or_equal' => 'Ngày đặt bàn phải từ hôm nay trở đi.',
        ];
    }
}

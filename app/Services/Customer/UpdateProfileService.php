<?php

namespace App\Services\Customer;

use App\Models\User;

class UpdateProfileService {
    public function __invoke(User $customer, array $validateData): array
    {
        $customer->fill($validateData);
        $message = 'Thông tin hồ sơ của bạn đã được cập nhật.';
        $requiresVerification = false;

        if ($customer->isDirty('email')) {
            $customer->email_verified_at = null;
            $customer->sendEmailVerificationNotification();

            $message = 'Đã cập nhật hồ sơ. Vui lòng kiểm tra hộp thư để xác thực Email mới của bạn!';
            $requiresVerification = true;
        }

        $customer->save();

        return [
            'message' => $message,
            'requires_verification' => $requiresVerification
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends Factory<UserAddress>
 */
class UserAddressFactory extends Factory
{
    protected static ?array $vietnamZones = null;

    public function definition(): array
    {
        if (is_null(self::$vietnamZones)) {
            $path = database_path('data/vietnam-zone.json');
            self::$vietnamZones = File::exists($path) ? json_decode(File::get($path), true) : [];
        }

        // Nếu file trống hoặc không tồn tại, dùng dữ liệu fallback để tránh crash
        if (empty(self::$vietnamZones)) {
            return [
                'user_id' => User::factory(),
                'receiver_name' => $this->faker->name(),
                'receiver_phone_number' => $this->faker->numerify('0#########'),
                'province_code' => 1,
                'ward_code' => 1,
                'address_detail' => $this->faker->streetAddress(),
                'is_default' => false,
            ];
        }

        // 1. Chọn ngẫu nhiên một Tỉnh/Thành phố
        $randomProvince = collect(self::$vietnamZones)->random();

        // 2. Chọn ngẫu nhiên một Xã/Phường thuộc Tỉnh đó
        $randomWard = collect($randomProvince['wards'])->random();

        return [
            'user_id' => User::factory(),
            'receiver_name' => $this->faker->name(),
            'receiver_phone_number' => $this->faker->numerify('0#########'),

            'province_code' => $randomProvince['code'],
            'ward_code' => $randomWard['code'],

            'address_detail' => $this->faker->streetAddress(),
            'is_default' => false,
        ];
    }
}

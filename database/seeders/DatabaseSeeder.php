<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Restoria',
            'email' => 'admin@restoria.com',
            'role' => 'admin'
        ]);

        User::factory(20)->create()->each(function ($user) {
            $addresses = UserAddress::factory(rand(1, 2))->create([
                'user_id' => $user->id,
                'receiver_name' => $user->name,
                'receiver_phone_number' => $user->phone_number,
            ]);

            $addresses->first()->update(['is_default' => true]);
        });

        $this->call([
            MenuSeeder::class
        ]);
    }
}

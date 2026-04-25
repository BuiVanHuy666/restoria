<?php

namespace Database\Seeders;

use App\Models\User;
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

        $this->call([
            MenuSeeder::class
        ]);
    }
}

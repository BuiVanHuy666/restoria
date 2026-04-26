<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/menu.sql');

        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}

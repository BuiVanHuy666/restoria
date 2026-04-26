<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();

            $table->string('receiver_name');
            $table->string('receiver_phone_number', 15);

            $table->unsignedSmallInteger('province_code');
            $table->unsignedSmallInteger('ward_code');
            $table->string('address_detail');

            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};

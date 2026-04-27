<?php

use App\Enums\MenuItemStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('image')->nullable();
            $table->string('status')->default(MenuItemStatus::AVAILABLE->value);
            $table
                ->boolean('is_new')->default(false)
                ->comment('Đánh dấu món ăn mới (1: Có, 0: Không)');
            $table
                ->boolean('is_popular')->default(false)
                ->comment('Đánh dấu món ăn bán chạy/nổi bật (1: Có, 0: Không)');
            $table
                ->boolean('is_round_image')->default(false)
                ->comment('Sử dụng layout ảnh tròn, không có viền bọc (1: Có, 0: Không)');;

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};

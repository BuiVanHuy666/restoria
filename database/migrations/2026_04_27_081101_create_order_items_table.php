<?php

use App\Models\MenuItem;
use App\Models\Promotion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->foreignIdFor(MenuItem::class)->constrained();

            $table->decimal('original_price', 12)->comment('Giá gốc của món');
            $table->decimal('discount_amount', 12)->default(0)->comment('Số tiền được giảm');
            $table->integer('quantity');
            $table->decimal('item_price', 12)->comment('Giá thực tế thanh toán');

            $table->foreignIdFor(Promotion::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

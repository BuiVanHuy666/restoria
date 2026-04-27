<?php

use App\Models\User;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();

            $table->foreignIdFor(User::class)->constrained();

            $table->string('customer_name');
            $table->string('customer_phone', 15)->nullable();

            $table->string('shipping_address');
            $table->string('shipping_ward');
            $table->string('shipping_province');

            $table->decimal('subtotal', 12)->comment('Tổng tiền hàng');
            $table->decimal('shipping_fee', 12)->default(0)->comment('Tiền vận chuyển');
            $table->decimal('discount', 12)->default(0)->comment('Tổng khuyển mãi');
            $table->decimal('total_amount', 12, 2)->comment('Số tiền thanh toán thực tế');

            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->timestamp('paid_at')->nullable();

            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

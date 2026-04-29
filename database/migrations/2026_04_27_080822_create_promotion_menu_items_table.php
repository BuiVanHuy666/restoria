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
        Schema::create('promotion_menu_items', function (Blueprint $table) {
            $table->foreignIdFor(Promotion::class)->constrained();
            $table->foreignIdFor(MenuItem::class)->constrained();
            $table->primary(['promotion_id', 'menu_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_menu_items');
    }
};

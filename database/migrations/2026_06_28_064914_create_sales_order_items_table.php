<?php

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
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            // どの受注の明細か。受注が消えたら明細も一緒に消す。
            $table->foreignId('sales_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained(); // どの商品か
            $table->integer('quantity');                    // 数量
            $table->integer('unit_price');                  // 受注時点の単価
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};

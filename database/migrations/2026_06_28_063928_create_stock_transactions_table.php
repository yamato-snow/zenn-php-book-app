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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained(); // どの商品の入出庫か
            $table->string('type');                         // in=入庫 / out=出庫
            $table->integer('quantity');                    // 動いた数量
            // この履歴がどの伝票（発注・受注）から来たかを記録する。
            $table->string('source_type')->nullable();      // 例：purchase_order / sales_order
            $table->unsignedBigInteger('source_id')->nullable(); // その伝票の id
            $table->date('transaction_date');               // 入出庫日
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};

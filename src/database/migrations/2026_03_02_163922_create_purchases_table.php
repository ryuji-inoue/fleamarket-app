<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            
            // 購入ユーザー
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // 購入された商品
            $table->foreignId('item_id')
                  ->unique()
                  ->constrained()
                  ->cascadeOnDelete();

            // 支払い方法
            $table->foreignId('payment_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // 配送先
            $table->string('postal_code');
            $table->string('address');
            $table->string('building')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}

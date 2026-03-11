<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

                // 出品者
                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

                // 商品名
                $table->string('name');

                // ブランド
                $table->string('brand')->nullable();

                // 商品説明
                $table->text('description');

                // 価格
                $table->integer('price');

                // 商品画像
                $table->string('image_path');

                // 商品状態（conditionsテーブル参照）
                $table->foreignId('condition_id')
                    ->constrained()
                    ->cascadeOnDelete();

                // 商品ステータス
                $table->tinyInteger('status')->default(0);
                // 0:販売中
                // 1:売却済

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
        Schema::dropIfExists('items');
    }
}

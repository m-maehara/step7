<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id'); // 外部キーとして定義する場合はunsignedBigIntegerを使用することが推奨されます
            $table->foreign('product_id')->references('id')->on('products'); // productsテーブルのidカラムを参照する外部キーを設定
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // 外部キー制約を削除
            $table->dropColumn('product_id'); // カラムを削除
        });
    }
}

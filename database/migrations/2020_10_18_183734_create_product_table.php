<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id');
            $table->string('product_name');
            $table->string('product_description');
            $table->string('product_image');
            $table->double('productprice',10,2);
            $table->timestamp('product_create_date')->nullable();
            $table->timestamp('product_change_date')->nullable();
            $table->boolean('product_offer_day');
            $table->integer('product_offer_day_order');
            $table->boolean('product_best_seller');
            $table->integer('product_best_seller_order');
            $table->integer('product_discount_percentage');
            $table->boolean('product_stock');
            $table->foreign('brand_id')->references('brand_id')->on('brands');
            $table->foreign('category_id')->references('category_id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}

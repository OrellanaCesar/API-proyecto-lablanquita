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
            $table->id('productid');
            $table->unsignedBigInteger('brandid');
            $table->unsignedBigInteger('categoryid');
            $table->string('productname');
            $table->string('productdescripcion');
            $table->string('productimage');
            $table->double('productprice',10,2);
            $table->timestamp('productcreatedate')->nullable();
            $table->timestamp('productchangedate')->nullable();
            $table->timestamp('productlowdate')->nullable();
            $table->boolean('productofferday');
            $table->boolean('productbestseller');
            $table->integer('productdiscountpercentage');
            $table->boolean('productstock');
            $table->foreign('brandid')->references('brandid')->on('brands');
            $table->foreign('categoryid')->references('categoryid')->on('categories');
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

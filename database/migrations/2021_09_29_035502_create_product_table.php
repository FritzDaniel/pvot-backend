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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id');
            $table->string('productName');
            $table->text('productDesc');
            $table->bigInteger('productQty');
            $table->string('productPicture')->nullable();
            $table->integer('productCategory');
            $table->double('productPrice');
            $table->double('productRevenue');
            $table->double('showPrice');
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
        Schema::dropIfExists('product');
    }
}

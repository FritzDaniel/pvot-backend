<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('emailToko');
            $table->string('handphoneToko');
            $table->string('namaToko');
            $table->string('alamatToko');
            $table->string('fotoToko')->nullable();
            $table->string('fotoHeaderToko')->nullable();
            $table->string('url_tokopedia')->nullable();
            $table->string('url_shopee')->nullable();
            $table->integer('category_id');
            $table->integer('supplier_id');
            $table->integer('design_id')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('Pending');
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
        Schema::dropIfExists('shops');
    }
}

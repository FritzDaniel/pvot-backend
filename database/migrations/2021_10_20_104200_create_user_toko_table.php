<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTokoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_toko', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('transaction_id');
            $table->integer('tokoCount');
            $table->integer('marketplaceCount');
            $table->integer('marketplaceSelect');
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
        Schema::dropIfExists('user_toko');
    }
}

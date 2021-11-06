<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('xendit_id')->nullable();
            $table->string('external_id')->nullable();
            $table->string('uniq_code')->nullable();
            $table->integer('user_id');
            $table->string('payment_channel');
            $table->string('payment_bank')->nullable();
            $table->string('email');
            $table->double('price');
            $table->string('status')->default('Pending');
            $table->string('description')->nullable();
            $table->string('receiptImage')->nullable();
            $table->string('receiptNumber')->nullable();
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
        Schema::dropIfExists('payments');
    }
}

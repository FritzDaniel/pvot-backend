<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutation', function (Blueprint $table) {
            $table->id();
            $table->string('bank_id');
            $table->string('account_number');
            $table->string('bank_type');
            $table->timestamp('date');
            $table->double('amount');
            $table->string('description');
            $table->string('type');
            $table->double('balance');
            $table->string('kode_unik');
            $table->string('id_order');
            $table->integer('user_id');
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
        Schema::dropIfExists('mutation');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('users');
            $table->string('pick_name');
            $table->string('pick_phone');
            $table->string('pick_address');
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->bigInteger('deliver_id')->unsigned();
            $table->string('deliver_name');
            $table->string('deliver_phone');
            $table->string('return_name')->nullable();
            $table->string('return_phone')->nullable();
            $table->string('return_address')->nullable();
            $table->integer('status');
            $table->integer('price');
            $table->dateTime('order_day');
            $table->dateTime('receive_day');
            $table->dateTime('return_day')->nullable();
            $table->integer('deliver_cost');
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
        Schema::dropIfExists('packages');
    }
};

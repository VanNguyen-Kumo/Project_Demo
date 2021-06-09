<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Order extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id');
            $table->integer('total_price');
            $table->string('delivery_address');
            $table->string('delivery_date');
            $table->integer('phone');
            $table->enum('status', OrderStatusType::getValues())
                ->default(OrderStatusType::WaitForConfirmation)->change();
            $table->uuid('user_id');
            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete ('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('orders');
    }
}

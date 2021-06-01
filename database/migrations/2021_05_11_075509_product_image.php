<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('porduct_images', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('image_url')->nullable();
            $table->uuid('product_id');
            $table->primary('id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete ('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('poduct_images');
    }
}

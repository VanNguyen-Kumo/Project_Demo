<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name')->unique();
            $table->integer('price');
            $table->string('description')->nullable();
            $table->integer('quantity');
            $table->uuid('category_id');
            $table->softDeletes('is_active')->nullable();
            $table->primary('id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete ('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('products');
    }
}

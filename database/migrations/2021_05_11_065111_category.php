<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Category extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name')->unique();
            $table->string('image_url')->nullable();
            $table->softDeletes('is_active')->nullable();
            $table->char('parent_category_id',36);
            $table->foreign('parent_category_id')->references('id')->on('categories')->onDelete ('cascade')->onUpdate('cascade');
            $table->primary('id');
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
        Schema::dropIfExists('categories');
    }
}

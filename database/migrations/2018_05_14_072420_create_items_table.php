<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('parent_category_id');
            $table->unsignedInteger('child_category_id');
            $table->string('title');
            $table->text('body');
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_category_id')->references('id')->on('categories');
            $table->foreign('child_category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}

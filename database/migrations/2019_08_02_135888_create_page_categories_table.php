<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('page_categories')) {
            Schema::create('page_categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('order')->nullable();
                $table->text('name')->nullable();
                $table->text('icon')->nullable();
                $table->longText('image')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_categories');
    }
}

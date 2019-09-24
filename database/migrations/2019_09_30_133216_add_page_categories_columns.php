<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPageCategoriesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumns('page_categories', ['slug','parent_id'])) {
            Schema::table('page_categories', function (Blueprint $table) {
                $table->text('slug')->nullable();
                $table->integer('parent_id')->nullable();
//                $table->longText('img');
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
        Schema::table('page_categories', function(Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('parent_id');
        });
    }
}

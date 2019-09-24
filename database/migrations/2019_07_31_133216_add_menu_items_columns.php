<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenuItemsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumns('menu_items', ['permissions','img_hover','img'])) {
            Schema::table('menu_items', function (Blueprint $table) {
                $table->longText('permissions')->nullable();
                $table->longText('img_hover')->nullable();
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
        Schema::table('menu_items', function(Blueprint $table) {
            $table->dropColumn('permissions');
            $table->dropColumn('img_hover');
            $table->dropColumn('img');
        });
    }
}

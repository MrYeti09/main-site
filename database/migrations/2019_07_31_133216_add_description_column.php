<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('menu_items', 'description')) {
            Schema::table('menu_items', function (Blueprint $table) {
                $table->mediumText('description')->nullable();
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
            $table->dropColumn('description');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataTypeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumns('page_blocks', ['clones','extra'])) {
            Schema::table('page_blocks', function (Blueprint $table) {
                $table->longText('extra')->nullable();
                $table->longText('clones')->nullable();
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
        Schema::table('page_blocks', function(Blueprint $table) {
            $table->dropColumn('extra');
            $table->dropColumn('clones');
        });
    }
}

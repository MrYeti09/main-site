<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingsFormfieldsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('settings_formfields', 'type')) {
            Schema::table('settings_formfields', function (Blueprint $table) {
                $table->text('type')->nullable();
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
        Schema::table('settings_formfields', function(Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}

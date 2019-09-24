<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsFormfieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('settings_formfields')) {
            Schema::create('settings_formfields', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('name')->nullable();
                $table->text('slug')->nullable();
                $table->text('route')->nullable();
                $table->text('type')->nullable();
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
        Schema::dropIfExists('settings_formfields');
    }
}

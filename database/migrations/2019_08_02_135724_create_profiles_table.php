<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('profiles')) {
            Schema::create('profiles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('name')->nullable();
                $table->text('text_font')->nullable();
                $table->text('text_fontweight')->nullable();
                $table->text('text_color')->nullable();
                $table->text('text_size')->nullable();
                $table->text('text_lineheight')->nullable();
                $table->text('text_letterspacing')->nullable();
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
        Schema::dropIfExists('profiles');
    }
}

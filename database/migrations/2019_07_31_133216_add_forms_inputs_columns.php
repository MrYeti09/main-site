<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormsInputsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumns('form_inputs', ['mask','second_mask','slug'])) {
            Schema::table('form_inputs', function (Blueprint $table) {
                $table->text('mask')->nullable();
                $table->text('second_mask')->nullable();
                $table->text('slug')->nullable();
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
        Schema::table('form_inputs', function(Blueprint $table) {
            $table->dropColumn('mask');
            $table->dropColumn('second_mask');
            $table->dropColumn('slug');
        });
    }
}

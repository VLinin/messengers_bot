<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateButtonStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialog_button_dialog_stage', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dialog_stage_id');
            $table->unsignedInteger('dialog_button_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('dialog_button_dialog_stage');
        Schema::enableForeignKeyConstraints();
    }
}

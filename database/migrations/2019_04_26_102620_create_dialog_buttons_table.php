<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDialogButtonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialog_buttons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sign_text');
            $table->string('send_text');
            $table->string('color');
            $table->boolean('order_mode')->default(false);
            $table->unsignedInteger('dialog_stage_id');
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
        Schema::dropIfExists('dialog_buttons');
        Schema::enableForeignKeyConstraints();
    }
}

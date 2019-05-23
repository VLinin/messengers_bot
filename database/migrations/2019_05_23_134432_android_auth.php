<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AndroidAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('android_auth', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login');
            $table->string('password');
        });
    }

    public function down()
    {
        Schema::dropIfExists('android_auth');
    }
}

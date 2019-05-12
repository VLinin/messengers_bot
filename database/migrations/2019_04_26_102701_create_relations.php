<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //product_feedbacks
        Schema::table('product_feedbacks',function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('service_id')->references('id')->on('services');
        });

        //order_products
        Schema::table('order_products',function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('order_id')->references('id')->on('orders');
        });

        //image_products
        Schema::table('image_products',function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('image_id')->references('id')->on('images');
        });

        //orders
        Schema::table('orders',function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('service_id')->references('id')->on('services');
        });

        //image_distributions
        Schema::table('image_distributions',function (Blueprint $table) {
            $table->foreign('image_id')->references('id')->on('images');
            $table->foreign('distribution_id')->references('id')->on('distributions');
        });

        //dialogs
        Schema::table('dialogs',function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('dialog_stage_id')->references('id')->on('dialog_stages');
            $table->foreign('service_id')->references('id')->on('services');
        });

        //order_statuses
        Schema::table('order_statuses',function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('status_id')->references('id')->on('statuses');
        });

        //dialog_stages
        Schema::table('dialog_stages',function (Blueprint $table) {
            $table->foreign('parent_stage_id')->references('id')->on('dialog_stages');
        });


        //distribution_services
        Schema::table('distribution_services',function (Blueprint $table) {
            $table->foreign('distribution_id')->references('id')->on('distributions');
            $table->foreign('service_id')->references('id')->on('services');
        });

        //products
        Schema::table('products',function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });

        //dialog_button_dialog_stages
        Schema::table('dialog_button_dialog_stages',function (Blueprint $table) {
            $table->foreign('dialog_stage_id')->references('id')->on('dialog_stages');
            $table->foreign('dialog_button_id')->references('id')->on('dialog_buttons');
        });

    }

}

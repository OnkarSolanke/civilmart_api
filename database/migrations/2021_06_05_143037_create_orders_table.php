<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->bigInteger('address_id')->unsigned()->nullable();
            $table->bigInteger('vendor_id')->unsigned()->nullable();
            $table->integer('quantity_required')->nullable();
            $table->enum('status', ['ORDER_PUT','ORDER_RECEIVED','ORDER_CANCLE','ENQUIRY','ORDER_CONFORM','ORDER_DISPATCH','DELIVERY',"PAYMENT"])->default('ORDER_PUT');
            $table->string('quantity_Details')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        
        //User foreign key
        Schema::table('orders',function($table){
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');
        });

        //Product foreign key
        Schema::table('orders',function($table){
            $table->foreign('product_id')
            ->references('id')->on('products')
            ->onDelete('cascade');
        });

        //Address foreign key
        Schema::table('orders',function($table){
            $table->foreign('address_id')
            ->references('id')->on('address')
            ->onDelete('cascade');
        });

        //Vendor foreign key
        Schema::table('orders',function($table){
            $table->foreign('vendor_id')
            ->references('id')->on('vendor')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

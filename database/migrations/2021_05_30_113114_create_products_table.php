<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->integer('price')->nullable();
            $table->string('unit')->nullable();
            $table->integer('available')->nullable();
            $table->string('description')->nullable();
            $table->string('avtar', 500)->nullable();
            $table->string('avtar_full_path', 500)->nullable();
            $table->timestamps(); 
           
        });
        Schema::table('products',function($table){
            $table->foreign('user_id')
            ->references('id')->on('users')
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
        Schema::dropIfExists('products');
    }
}

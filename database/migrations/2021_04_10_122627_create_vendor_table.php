<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('midle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('mobile')->nullable();
            $table->string('about',500)->nullable();
            $table->string('material',100)->nullable();
            $table->string('adhar_no',17)->nullable();
            $table->enum('status', ['Active','In-active'])->default('In-active');
            $table->string('avtar', 500)->nullable();
            $table->string('avtar_full_path', 500)->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('vendor');
    }
}

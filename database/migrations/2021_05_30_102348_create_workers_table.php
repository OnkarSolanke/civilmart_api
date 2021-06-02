<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('midle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('mobile')->nullable();
            $table->string('about',500)->nullable();
            $table->string('adhar_no',17)->nullable();
            $table->string('qualification',100)->nullable();
            $table->string('skill',100)->nullable();
            $table->enum('status', ['Active','In-active'])->default('In-active');
            $table->string('avtar', 500)->nullable();
            $table->string('avtar_full_path', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workers');
    }
}

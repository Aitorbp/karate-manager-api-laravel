<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('own_budget');
            $table->integer('points')->default('0');
            $table->boolean('admin_user_group')->default('0');

            $table->integer('id_user')->unsigned()->nullable();
            $table->integer('id_group')->unsigned()->nullable();
           
            $table->foreign('id_group')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
           
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
        Schema::dropIfExists('participants');
    }
}

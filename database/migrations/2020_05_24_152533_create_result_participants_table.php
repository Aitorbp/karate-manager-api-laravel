<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('points');
            $table->integer('points_total');
    
            

            $table->integer('id_championship')->unsigned()->nullable();
            $table->integer('id_participant')->unsigned()->nullable();

            $table->foreign('id_participant')->references('id')->on('participants')->onDelete('cascade');;
            $table->foreign('id_championship')->references('id')->on('championship')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_participants');
    }
}

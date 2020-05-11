<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidBetweenRivals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_between_rivals', function (Blueprint $table) {
           
            $table->increments('id');
            $table->timestamps();
            $table->integer('bid_rival');

            
            $table->integer('id_karateka')->unsigned()->nullable();
            $table->integer('id_participant_bid_send')->unsigned()->nullable();
            $table->integer('id_participant_bid_receive')->unsigned()->nullable();

            $table->foreign('id_karateka')->references('id')->on('karatekas')->onDelete('cascade');
            $table->foreign('id_participant_bid_send')->references('id')->on('participants')->onDelete('cascade');;
            $table->foreign('id_participant_bid_receive')->references('id')->on('participants')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bid_between_rivals');
    }
}

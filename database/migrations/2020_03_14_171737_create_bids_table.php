<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('bid');
            $table->dateTime('start_hour_bid')->nullable();
            $table->integer('id_group')->unsigned()->nullable();
            $table->integer('id_participants')->unsigned()->nullable();
            $table->integer('id_karatekas')->unsigned()->nullable();

            $table->foreign('id_participants')->references('id')->on('participants')->onDelete('cascade');;
            $table->foreign('id_group')->references('id')->on('groups')->onDelete('cascade');;
            $table->foreign('id_karatekas')->references('id')->on('karatekas')->onDelete('cascade');;
  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bids');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
    
            $table->unsignedBigInteger('bid_participant');

            $table->integer('id_group')->unsigned()->nullable();
            $table->integer('id_participants')->unsigned()->nullable();;
            $table->integer('id_karatekas')->unsigned()->nullable();;
            
            $table->foreign('id_participants')->references('id')->on('participants')->onDelete('cascade');;
            $table->foreign('id_group')->references('id')->on('groups')->onDelete('cascade');;
            $table->foreign('id_karatekas')->references('id')->on('karatekas')->onDelete('cascade');;
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
        Schema::dropIfExists('sales');
    }
}

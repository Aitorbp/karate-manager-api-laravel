<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultKaratekasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_karatekas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('points');
            $table->integer('points_total');
            $table->boolean('injured')->default('0');
            $table->boolean('discontinued')->default('0');
            
            $table->integer('id_karateka')->unsigned()->nullable();
            $table->integer('id_championship')->unsigned()->nullable();

            $table->foreign('id_karateka')->references('id')->on('karatekas')->onDelete('cascade');
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
        Schema::dropIfExists('result_karatekas');
    }
}

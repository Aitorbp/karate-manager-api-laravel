<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->dateTime('date_release')->nullable();

            $table->integer('id_group')->unsigned()->nullable();
            $table->integer('id_karatekas')->unsigned()->nullable();

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
        Schema::dropIfExists('market');
    }
}

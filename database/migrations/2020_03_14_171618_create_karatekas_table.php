<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaratekasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karatekas', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name')->unique();
            $table->string('country');
            $table->boolean('gender');
            $table->enum('weight',['-60','-67','-75', '-84', '+84', '-50', '-55', '-61', '-68', '+68']);
            $table->integer('value');
            $table->timestamps();
            $table->string('photo_karateka')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karatekas');
    }
}

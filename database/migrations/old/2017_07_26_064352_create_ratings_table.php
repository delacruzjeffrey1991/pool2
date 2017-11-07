<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('player_id');
          $table->bigInteger('async_real');
          $table->bigInteger('async_virtual');
          $table->bigInteger('normal_real');
          $table->bigInteger('normal_virtual');
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
        Schema::drop('ratings');
    }
}

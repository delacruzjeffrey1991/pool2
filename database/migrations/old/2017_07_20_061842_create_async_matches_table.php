<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsyncMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('async_matches', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('player1_id');
          $table->bigInteger('player2_id');
          $table->bigInteger('activity1_id');
          $table->bigInteger('activity2_id');
          $table->integer('score1');
          $table->integer('score2');
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
        Schema::drop('async_matches');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiveMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_matches', function (Blueprint $table) {
            $table->bigIncrements('id');
          $table->bigInteger('player1_id');
          $table->bigInteger('player2_id');
          $table->bigInteger('winner_id');
	  $table->string('room_name');	

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
        Schema::drop('live_matches');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GameWinnerAsyncMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('async_matches', function (Blueprint $table) {
            $table->string('player1_name');
	    $table->string('player2_name');
  	    $table->string('winners_name');
	    $table->bigInteger('winners_id');
	    $table->integer('winners_score');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('async_matches', function (Blueprint $table) {
            //
        });
    }
}

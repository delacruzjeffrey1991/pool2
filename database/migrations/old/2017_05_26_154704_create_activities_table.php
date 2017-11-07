<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('player_id');
          $table->bigInteger('lobby_id');
          $table->integer('pocketed_balls')->default(0);
          $table->integer('duration')->default(0);
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
      Schema::dropIfExists('activities');
    }
}

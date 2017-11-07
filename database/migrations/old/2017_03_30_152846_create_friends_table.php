<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('friends', function (Blueprint $table) {

          $table->bigIncrements('id');
          $table->bigInteger('player_id');
          $table->bigInteger('friend_id');
          $table->boolean('is_accepted')->default(0); //if accepted, created counter part
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
      Schema::dropIfExists('friends');
    }
}

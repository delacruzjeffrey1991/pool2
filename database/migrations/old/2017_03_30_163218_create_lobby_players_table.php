<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLobbyPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('lobby_players', function (Blueprint $table) {

          $table->bigIncrements('id');
          $table->bigInteger('player_id');
          $table->bigInteger('lobby_id');
          $table->boolean('is_winner')->default(0);
          $table->text('lobby_meta')->nullable(); //stores the meta of the lobby for completed and archived
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
      Schema::dropIfExists('lobby_players');      
    }
}

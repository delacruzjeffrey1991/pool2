<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
// use DB;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('players', function (Blueprint $table) {

          $table->bigIncrements('id');
          $table->string('game_id', 10)->nullable();
          $table->string('playfab_id')->nullable();
          $table->string('firstname')->nullable();
          $table->string('lastname')->nullable();
          $table->string('username')->nullable();
          $table->string('email')->unique();
          $table->string('password', 100);
          $table->string('api_key', 300)->nullable();
          $table->string('session_ticket', 300)->nullable();

          $table->decimal('virtual_chips_total', 15, 2)->default(0.00);
          $table->decimal('real_cash_total', 15, 2)->default(0.00);

          $table->integer('total_won')->default(0);
          $table->integer('total_lose')->default(0);
          $table->integer('percentage_won')->default(0);
          $table->integer('percentage_lose')->default(0);

          $table->string('android_device_id')->nullable();
          $table->string('ios_device_id')->nullable();

          $table->text('player_meta')->nullable();
          $table->text('player_payment_meta')->nullable(); //payment ids ['paypal'=>'email', 'strip'=>'CC DETAILS']

          $table->enum('vc', ['cash', 'chip', 'point', 'item'])->nullable(); //type of vc
          $table->integer('last_login')->nullable();

          $table->text('avatar')->nullable();

          $table->rememberToken();
          $table->timestamps();

      });
      // DB::unprepared("ALTER TABLE players AUTO_INCREMENT = 1000");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('players');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
// use DB;

class CreateLobbiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('lobbies', function (Blueprint $table) {

          $table->bigIncrements('id');
          $table->string('uri', 100)->nullable();
          $table->string('name', 100)->nullable();
          $table->string('region', 50)->nullable();

          $table->decimal('amount', 15, 2)->default(0.0);
          $table->enum('vc', ['cash', 'chip', 'point', 'item'])->nullable();
          $table->string('currency', 5)->nullable();

          $table->bigInteger('winner_id')->nullable();
          $table->bigInteger('loser_id')->nullable();

          $table->boolean('has_player_accept')->default(0);
          $table->integer('player_accepted_at')->nullable();
          $table->boolean('has_opponent_accept')->default(0);
          $table->integer('opponent_accepted_at')->nullable();

          $table->boolean('is_active')->default(1);
          $table->boolean('is_user_created')->default(1); //if created by user

          $table->bigInteger('created_by')->nullable();
          $table->enum('status', ['created', 'started', 'completed', 'archived']);

          $table->integer('started_at')->nullable();
          $table->integer('completed_at')->nullable();
          $table->integer('archived_at')->nullable();
          $table->timestamps();

      });
      // DB::unprepared("ALTER TABLE lobbies AUTO_INCREMENT = 1000");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('lobbies');
    }
}

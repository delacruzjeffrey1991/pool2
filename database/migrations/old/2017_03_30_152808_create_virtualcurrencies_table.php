<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
// use DB;

class CreateVirtualcurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('vcs', function (Blueprint $table) {

          $table->bigIncrements('id');
          $table->enum('mode', ['cash', 'chip', 'point', 'item'])->nullable();
          $table->bigInteger('amount')->unsigned()->nullable(); //amount credited
          $table->bigInteger('player_id', FALSE); //player who made the purchase

          $table->string('payment_type')->nullable(); //payment type: paypal, etc
          $table->string('payment_number')->nullable(); //payment number from ex paypal
          $table->text('payment_meta')->nullable(); // serialize payment info

          $table->boolean('is_added')->default(1);
          $table->boolean('is_available')->default(1); //if 0, not included
          $table->boolean('is_credited')->default(0); //if credited by admin
          $table->bigInteger('creditor_id')->nullable(); //id of admin who credited

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
      Schema::dropIfExists('vcs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDletedReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('deleted_reservations');

        Schema::create('deleted_reservations', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->integer('reservation_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('reservation_id')->references('id')->on('reservations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deleted_reservations');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoreFieldToFlightTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->text('depart_airport')->nullable();
            $table->text('arrival_airport')->nullable();
            $table->text('flight_class')->nullable();
            $table->text('flight_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('more_field_to_flight');
    }
}

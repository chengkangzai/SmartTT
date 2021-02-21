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
            $table->foreignId('depart_airport')->references('id')->on('airports');
            $table->foreignId('arrival_airport')->references('id')->on('airports');
            $table->text('flight_class');
            $table->text('flight_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn('depart_airport');
            $table->dropColumn('arrival_airport');
            $table->dropColumn('flight_class');
            $table->dropColumn('flight_type');
        });
    }
}

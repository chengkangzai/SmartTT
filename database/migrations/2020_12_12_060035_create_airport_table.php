<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('IATA')->nullable();
            $table->string('ICAO')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('altitude')->nullable();
            $table->string('offset_UTC')->nullable();
            $table->string('DST')->nullable();
            $table->string('timezoneTz')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airports');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->dateTime('depart_time');
            $table->dateTime('arrive_time');
            $table->integer('fee');
            $table->foreignId('airline_id')->constrained();
            $table->foreignId('departure_airport')->references('id')->on('airports');
            $table->foreignId('arrival_airport')->references('id')->on('airports');
            $table->text('class');
            $table->text('type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flights');
    }
}


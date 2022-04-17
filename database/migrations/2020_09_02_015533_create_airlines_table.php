<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirlinesTable extends Migration
{
    public function up()
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('country_id')->constrained();
            $table->string('ICAO');
            $table->string('IATA');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('airlines');
    }
}

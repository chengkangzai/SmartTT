<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourDescriptionTable extends Migration
{
    public function up()
    {
        Schema::create('tour_description', function (Blueprint $table) {
            $table->id();
            $table->string('place');
            $table->text('description');
            $table->foreignId('tour_id')->references('id')->on('tours');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_description');
    }
}

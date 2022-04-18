<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('country_tour', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained();
            $table->foreignId('tour_id')->constrained();
            $table->integer('order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('country_tour');
    }
};

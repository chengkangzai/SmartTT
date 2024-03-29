<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightPackageTable extends Migration
{
    public function up()
    {
        Schema::create('flight_package', function (Blueprint $table) {
            $table->foreignId('flight_id')->constrained();
            $table->foreignId('package_id')->constrained();
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flight_package');
    }
}

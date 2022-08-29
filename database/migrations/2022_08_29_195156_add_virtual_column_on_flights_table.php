<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->string('airline')->after('airline_id')->nullable();
        });

        //update all flights with the airline name
        DB::table('flights')->update(['airline' => DB::raw('(SELECT name FROM airlines WHERE id = flights.airline_id)')]);

        Schema::table('flights', function (Blueprint $table) {
            $table->string('name')->virtualAs('CONCAT(airline, " (", departure_date, ") -> (", arrival_date, ")")')->after('airline');
        });
    }

    public function down()
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('airline');
        });
    }
};

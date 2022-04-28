<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('itinerary_url');
        }); // for the sake of SQLite
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('thumbnail_url');
        });
    }

    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('itinerary_url')->nullable();
            $table->string('thumbnail_url')->nullable();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('country_tour', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }

    public function down()
    {
        Schema::table('country_tour', function (Blueprint $table) {
            $table->integer('order');
        });
    }
};

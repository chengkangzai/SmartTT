<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('package_pricings', function (Blueprint $table) {
            $table->integer('price')->change();
        });
    }

    public function down()
    {
        Schema::table('package_pricings', function (Blueprint $table) {
            $table->string('price')->change();
        });
    }
};

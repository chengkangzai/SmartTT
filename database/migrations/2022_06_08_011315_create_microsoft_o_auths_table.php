<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('microsoft_o_auths', function (Blueprint $table) {
            $table->id();
            $table->text('accessToken');
            $table->text('refreshToken');
            $table->text('tokenExpires');
            $table->string('userName');
            $table->string('userEmail');
            $table->string('userTimeZone');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('microsoft_o_auths');
    }
};

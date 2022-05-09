<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('booking_id')->constrained();
            $table->string('payment_method')->nullable();
            $table->string('status');
            $table->integer('amount');
            $table->string('payment_type');

            $table->string('card_number')->nullable();
            $table->string('card_expiry_date')->nullable();
            $table->string('card_cvc')->nullable();
            $table->string('card_holder_name')->nullable();

            $table->string('billing_name')->nullable();
            $table->string('billing_phone')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};

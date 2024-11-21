<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('room_booking'))
        {
            Schema::create('room_booking', function (Blueprint $table) {
                $table->id();
                $table->string('booking_number');
                $table->bigInteger('total')->default(0);
                $table->bigInteger('coupon_id')->default(0);
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->default('india');
                $table->string('zipcode')->nullable();
                $table->bigInteger('user_id')->default(0);
                $table->string('payment_method')->nullable();
                $table->string('payment_status')->default(0)->comment('0 = unpaid , 1 = paid');
                $table->string('invoice')->nullable();
                $table->integer('workspace')->default(0);
                $table->integer('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_booking');
    }
};

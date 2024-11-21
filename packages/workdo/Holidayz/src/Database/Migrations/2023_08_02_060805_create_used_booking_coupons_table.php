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
        if(!Schema::hasTable('used_booking_coupons'))
        {
            Schema::create('used_booking_coupons', function (Blueprint $table) {
                $table->id();
                $table->integer('customer_id');
                $table->integer('coupon_id');
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
        Schema::dropIfExists('used_booking_coupons');
    }
};

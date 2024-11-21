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
        if(!Schema::hasTable('room_booking_carts'))
        {
            Schema::create('room_booking_carts', function (Blueprint $table) {
                $table->id();
                $table->integer('customer_id')->default(0);
                $table->integer('room_id')->default(0);
                $table->integer('room')->default(0);
                $table->integer('workspace')->default(0);
                $table->date('check_in')->nullable();
                $table->date('check_out')->nullable();
                $table->integer('price')->default(0)->nullable();
                $table->integer('service_charge')->default(0)->nullable();
                $table->string('services')->nullable();
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
        Schema::dropIfExists('room_booking_carts');
    }
};

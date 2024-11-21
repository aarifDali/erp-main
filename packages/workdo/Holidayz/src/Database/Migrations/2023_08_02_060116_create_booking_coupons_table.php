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
        if(!Schema::hasTable('booking_coupons'))
        {
            Schema::create('booking_coupons', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code');
                $table->float('discount')->default('0.00');
                $table->integer('limit')->default('0');
                $table->text('description')->nullable();
                $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('booking_coupons');
    }
};

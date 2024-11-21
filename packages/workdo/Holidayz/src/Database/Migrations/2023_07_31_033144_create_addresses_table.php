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
        if(!Schema::hasTable('addresses'))
        {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id');
                $table->string('alias')->nullable();
                $table->string('address')->nullable();
                $table->string('address_2')->nullable();
                $table->string('city')->nullable();
                $table->string('zip_code')->nullable();
                $table->string('state')->nullable();
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
        Schema::dropIfExists('addresses');
    }
};

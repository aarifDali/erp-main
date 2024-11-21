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
        if(!Schema::hasTable('hotel_services'))
        {
            Schema::create('hotel_services', function (Blueprint $table) {
                $table->id();
                $table->integer('workspace')->nullable();
                $table->string('name');
                $table->integer('position')->default(1);
                $table->string('image')->nullable();
                $table->string('icon')->nullable();
                $table->integer('created_by')->default(0);
                $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('hotel_services');
    }
};

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
        if(!Schema::hasTable('rooms'))
        {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('room_type');
                $table->text('short_description')->nullable();
                $table->longText('description')->nullable();
                $table->string('tags')->nullable();
                $table->integer('adults')->default(0);
                $table->integer('children')->default(0);
                $table->integer('total_room')->default(0);
                $table->integer('base_price')->default(0);
                $table->integer('final_price')->default(0);
                $table->string('image')->nullable();
                $table->integer('is_active')->default(0);
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
        Schema::dropIfExists('rooms');
    }
};

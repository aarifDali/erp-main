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
        if(!Schema::hasTable('rooms_features'))
        {
            Schema::create('rooms_features', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('position')->nullable();
                $table->string('icon')->nullable();
                $table->integer('status')->default(1);
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
        Schema::dropIfExists('rooms_features');
    }
};

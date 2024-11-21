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
        if(!Schema::hasTable('hotel_theme_settings'))
        {
            Schema::create('hotel_theme_settings', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('name/pagename');
                $table->text('value')->nullable()->comment('value/json_value');
                $table->string('type')->nullable();
                $table->string('theme_name');
                $table->integer('workspace')->nullable();
                $table->integer('created_by');
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
        Schema::dropIfExists('hotel_theme_settings');
    }
};

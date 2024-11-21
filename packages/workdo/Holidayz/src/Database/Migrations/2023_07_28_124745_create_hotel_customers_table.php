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
        if(!Schema::hasTable('hotel_customers'))
        {
            Schema::create('hotel_customers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('type', 20)->default('customer');
                $table->string('avatar', 100)->nullable();
                $table->string('lang', 100)->nullable();
                $table->integer('is_active')->default(1);
                $table->integer('delete_status')->default(1);
                $table->string('last_name')->nullable();
                $table->string('dob')->nullable();
                $table->string('newsletter')->default(0);
                $table->string('opt_in')->default(0);
                $table->string('group_access')->nullable();
                $table->string('id_number')->nullable();
                $table->string('company')->nullable();
                $table->string('vat_number')->nullable();
                $table->string('home_phone')->nullable();
                $table->string('mobile_phone')->nullable();
                $table->string('other')->nullable();
                $table->integer('workspace')->default(0);
                $table->rememberToken();
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
        Schema::dropIfExists('hotel_customers');
    }
};

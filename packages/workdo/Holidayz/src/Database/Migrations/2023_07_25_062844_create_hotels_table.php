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
        if(!Schema::hasTable('hotels'))
        {
            Schema::create('hotels', function (Blueprint $table) {
                $table->id();
                $table->integer('workspace')->nullable();
                $table->string('name');
                $table->string('slug')->unique()->nullable();
                $table->string('email')->nullable();
                $table->string('hotel_theme')->nullable();
                $table->string('theme_dir')->nullable();
                $table->string('phone')->nullable();
                $table->string('ratting')->nullable();
                $table->string('check_in')->nullable();
                $table->string('check_out')->nullable();
                $table->text('short_description')->nullable();
                $table->string('address')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('zip_code')->nullable();
                $table->longText('policy')->nullable();
                $table->string('logo')->nullable();
                $table->string('invoice_logo')->nullable();
                $table->longText('description')->nullable();
                $table->string('enable_domain')->default('off');
                $table->string('domains')->nullable();
                $table->string('enable_subdomain')->default('off');
                $table->string('subdomain')->nullable();
                $table->string('enable_storelink')->default('on');
                $table->integer('created_by')->default(0);
                $table->integer('is_active')->default(1);
                $table->text('mail_driver')->nullable();
                $table->text('mail_host')->nullable();
                $table->text('mail_port')->nullable();
                $table->text('mail_username')->nullable();
                $table->text('mail_password')->nullable();
                $table->text('mail_encryption')->nullable();
                $table->text('mail_from_address')->nullable();
                $table->text('mail_from_name')->nullable();
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
        Schema::dropIfExists('hotels');
    }
};

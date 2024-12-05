<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
             $table->string('email')->nullable(false)->change();
             $table->string('contact')->nullable(false)->change();
 
             $table->string('billing_name')->nullable()->change();
             $table->string('billing_phone')->nullable()->change();
             $table->string('billing_zip')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('contact')->nullable()->change();

            $table->string('billing_name')->nullable(false)->change();
            $table->string('billing_phone')->nullable(false)->change();
            $table->string('billing_zip')->nullable(false)->change();
        });
    }
};

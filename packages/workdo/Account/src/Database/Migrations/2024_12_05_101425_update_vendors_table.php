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
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('contact')->nullable(false)->change();
            $table->string('billing_country')->nullable(false)->change();
            $table->string('billing_state')->nullable(false)->change();
            $table->string('billing_city')->nullable(false)->change();
            $table->string('billing_address')->nullable(false)->change();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('contact')->nullable()->change();
            $table->string('billing_country')->nullable()->change();
            $table->string('billing_state')->nullable()->change();
            $table->string('billing_city')->nullable()->change();
            $table->string('billing_address')->nullable()->change();
        });
    }
};

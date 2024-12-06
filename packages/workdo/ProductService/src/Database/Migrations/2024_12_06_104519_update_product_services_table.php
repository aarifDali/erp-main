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
        Schema::table('product_services', function (Blueprint $table) {
            $table->string('sku')->nullable()->change();
            $table->integer('unit_id')->nullable()->change();
            $table->float('purchase_price', 20)->nullable()->change();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_services', function (Blueprint $table) {
            $table->string('sku')->nullable(false)->change();
            $table->integer('unit_id')->nullable(false)->change();
            $table->float('purchase_price', 20)->nullable(false)->change();
        });
    }
};

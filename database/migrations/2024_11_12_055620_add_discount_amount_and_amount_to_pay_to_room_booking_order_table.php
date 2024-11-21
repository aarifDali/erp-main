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
        Schema::table('room_booking_order', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->nullable()->after('price');
            $table->decimal('amount_to_pay', 10, 2)->default(0)->after('discount_amount');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_booking_order', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'amount_to_pay']);
        });
    }
};

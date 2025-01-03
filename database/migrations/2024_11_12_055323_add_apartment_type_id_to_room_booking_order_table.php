<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('room_booking_order', function (Blueprint $table) {
            $table->foreignId('apartment_type_id')->nullable()->constrained('apartment_types')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('room_booking_order', function (Blueprint $table) {
            $table->dropForeign(['apartment_type_id']);
            $table->dropColumn('apartment_type_id');
        });
    }
};

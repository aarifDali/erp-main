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
        Schema::table('daily_report_tasks', function (Blueprint $table) {
            $table->dropColumn('time_spent'); 
            $table->time('start_time')->nullable()->after('description'); 
            $table->time('end_time')->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_report_tasks', function (Blueprint $table) {
            $table->decimal('time_spent', 8, 2)->default(0); 
            $table->dropColumn(['start_time', 'end_time']); 
        });
    }
};

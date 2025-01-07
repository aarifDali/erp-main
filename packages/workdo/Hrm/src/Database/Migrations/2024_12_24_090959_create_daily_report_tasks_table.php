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
        Schema::create('daily_report_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_report_id');
            $table->text('description'); 
            $table->decimal('time_spent', 8, 2)->default(0); 
            $table->enum('status', ['In progress', 'Completed', 'Pending'])->default('In progress');     
            $table->string('attachment')->nullable(); 
            $table->timestamps();

            $table->foreign('daily_report_id')->references('id')->on('daily_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_report_tasks');
    }
};
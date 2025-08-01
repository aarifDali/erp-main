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
    Schema::create('task_assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
        $table->foreignId('client_id')->constrained('assistnow_clients')->onDelete('cascade');
        $table->foreignId('service_id')->constrained('assistnow_services')->onDelete('cascade');
        $table->decimal('fund_recieved', 10, 2);
        $table->date('service_date')->nullable();
        $table->integer('time_spent'); 
        $table->decimal('service_charge', 10, 2); 
        $table->decimal('total_charge', 10, 2); 
        $table->integer('workspace')->nullable();
        $table->integer('created_by')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};

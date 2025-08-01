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
        Schema::create('assistnow_clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->unique(); 
            $table->string('name'); 
            $table->foreignId('debtor_id')->nullable()->constrained('assistnow_debtors')->nullOnDelete(); 
            $table->string('phone');
            $table->string('email')->nullable()->unique();
            $table->integer('workspace')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistnow_clients');
    }
};

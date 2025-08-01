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
        Schema::create('client_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('assistnow_clients')->cascadeOnDelete();
            $table->string('contact_name'); 
            $table->string('relationship');
            $table->string('phone');
            $table->string('phone_2')->nullable();
            $table->string('phone_extra')->nullable();
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_relations');
    }
};

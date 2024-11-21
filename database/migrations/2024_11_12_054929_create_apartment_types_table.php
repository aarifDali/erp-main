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
        if (!Schema::hasTable('apartment_types')) {
            Schema::create('apartment_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('status');
                $table->timestamps();
            });

            Schema::table('rooms', function (Blueprint $table) {
                $table->foreignId('apartment_type_id')->nullable()->constrained('apartment_types')->onDelete('set null');
            });

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_types');
    }
};

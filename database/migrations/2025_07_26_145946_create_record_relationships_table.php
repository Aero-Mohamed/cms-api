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
        Schema::create('record_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relationship_id')->constrained('entity_relationships')->cascadeOnDelete();
            $table->foreignId('from_record_id')->constrained('records')->cascadeOnDelete();
            $table->foreignId('to_record_id')->constrained('records')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_relationships');
    }
};

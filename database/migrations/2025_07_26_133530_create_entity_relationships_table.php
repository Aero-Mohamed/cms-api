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
        Schema::create('entity_relationships', function (Blueprint $table) {
            $table->id();
            $table->string('type', 10);
            $table->string('name', 100);
            $table->string('inverse_name', 100);
            $table->foreignId('from_entity_id')->constrained('entities')->cascadeOnDelete();
            $table->foreignId('to_entity_id')->constrained('entities')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_relationships');
    }
};

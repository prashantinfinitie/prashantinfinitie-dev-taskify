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
        Schema::create('lead_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('lead_forms');
            $table->string('label');
            $table->string('name'); // for mapping to lead fields
            $table->string('type'); // text, textarea, select, checkbox
            $table->boolean('is_required')->default(false);
            $table->boolean('is_mapped')->default(false); // true if this maps to leads table
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_form_fields');
    }
};

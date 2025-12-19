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
        Schema::create('event_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_request_id')
                ->constrained('event_requests')
                ->cascadeOnDelete();

            $table->string('event_name');
            $table->date('event_date');
            $table->string('location')->nullable();

            $table->boolean('has_food')->nullable();
            $table->string('food_description')->nullable();

            $table->boolean('has_gift')->nullable();
            $table->string('gift_description')->nullable();

            $table->boolean('has_external_contribution')->nullable();
            $table->text('external_contribution_description')->nullable();

            // (demais campos do formulário)
            // ...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_forms');
    }
};

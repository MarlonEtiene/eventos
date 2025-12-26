<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_forms', function (Blueprint $table) {
            $table->id();

            /** Identification */
            $table->foreignId('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('function')->nullable();
            $table->string('sector')->nullable();
            $table->string('phone')->nullable();

            /** Event Information */
            $table->foreignId('event_request_id')->nullable();
            $table->string('event_title')->nullable();
            $table->dateTime('event_date')->nullable();
            $table->time('event_start_time')->nullable();
            $table->time('event_end_time')->nullable();
            $table->string('location')->nullable();
            $table->json('target_audience')->nullable();
            $table->json('others_audience')->nullable();
            $table->json('estimated_audience')->nullable();

            /** Description */
            $table->text('description')->nullable();
            $table->text('objective')->nullable();
            $table->text('activities')->nullable();
            $table->text('resources')->nullable();
            $table->text('responsibles')->nullable();

            /** Especial Items */
            $table->boolean('with_snack')->default(false);
            $table->string('snack_description')->nullable();
            $table->boolean('with_gift')->default(false);
            $table->string('gift_description')->nullable();

            $table->boolean('with_contribution')->default(false);
            $table->text('contribution_description')->nullable();

            $table->boolean('declaration')->default(false);

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

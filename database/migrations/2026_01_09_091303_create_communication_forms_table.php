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
        Schema::create('communication_forms', function (Blueprint $table) {
            $table->id();

            /** Identification (snapshot) */
            $table->string('name')->nullable();
            $table->string('function')->nullable();
            $table->string('sector')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            /** Communication Info */
            $table->string('title')->nullable();
            $table->string('communication_type')->nullable();
            // ex: campanha, aviso, institucional

            $table->json('target_audience')->nullable();
            $table->string('others_audience')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            /** Channels */
            $table->boolean('use_intranet')->default(false);
            $table->boolean('use_email')->default(false);
            $table->boolean('use_social_media')->default(false);
            $table->boolean('use_printed_material')->default(false);

            /** Description */
            $table->text('message')->nullable();
            $table->text('observations')->nullable();

            /** Declaration */
            $table->boolean('declaration')->default(false);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_forms');
    }
};

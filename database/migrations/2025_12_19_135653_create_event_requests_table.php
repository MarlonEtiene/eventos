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
        Schema::create('event_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')
                ->constrained('users');

            $table->string('status');

            $table->foreignId('admin_reviewed_by')
                ->nullable()
                ->constrained('users');
            $table->timestamp('admin_reviewed_at')->nullable();
            $table->text('admin_rejection_reason')->nullable();

            $table->foreignId('direction_reviewed_by')
                ->nullable()
                ->constrained('users');
            $table->timestamp('direction_reviewed_at')->nullable();
            $table->text('direction_rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_requests');
    }
};

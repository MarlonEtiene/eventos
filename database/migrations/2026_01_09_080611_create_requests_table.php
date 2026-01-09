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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            // Quem solicitou
            $table->foreignId('requester_id')
                ->constrained('users');

            // Polimórfico (event_form ou communication_form)
            $table->morphs('requestable');
            // cria: requestable_id, requestable_type

            // Status global
            $table->string('status')->index();

            /**
             * ADMIN
             * - Pode reprovar
             * - NÃO aprova
             */
            $table->foreignId('admin_reviewed_by')
                ->nullable()
                ->constrained('users');

            $table->timestamp('admin_reviewed_at')->nullable();
            $table->text('admin_rejection_reason')->nullable();

            /**
             * DIRECTORSHIP
             * - Pode aprovar
             * - Pode reprovar
             */
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
        Schema::dropIfExists('requests');
    }
};

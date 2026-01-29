<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();

            /**
             * Quem solicitou (usuário autenticado)
             */
            $table->foreignId('requester_id')
                ->constrained('users');

            /**
             * Snapshot do solicitante
             */
            $table->string('email');
            $table->string('name');
            $table->string('function')->nullable();
            $table->string('sector')->nullable();
            $table->string('phone')->nullable();

            /**
             * Decisão de fluxo
             */
            $table->boolean('has_event')->nullable();

            /**
             * Dados do evento
             */
            $table->string('title')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->foreignId('local_id')->nullable();

            $table->json('target_audience')->nullable();
            $table->string('others_audience')->nullable();
            $table->string('estimated_audience')->nullable();

            $table->text('objective')->nullable();
            $table->text('activities')->nullable();
            $table->text('resources')->nullable();
            $table->text('responsibles')->nullable();

            /**
             * Itens especiais
             */
            $table->boolean('with_snack')->nullable();
            $table->text('snack_description')->nullable();

            $table->boolean('with_gift')->nullable();
            $table->text('gift_description')->nullable();

            $table->boolean('with_contribution')->nullable();
            $table->text('contribution_description')->nullable();

            /**
             * Comunicação
             */
            $table->string('communication_type')->nullable();
            $table->string('communication_type_other')->nullable();
            $table->text('art_image_text')->nullable();
            $table->timestamp('delivery_date')->nullable();

            /**
             * Ciente
             */
            $table->boolean('aware')->default(false);

            /**
             * Declaração
             */
            $table->boolean('declaration')->default(false);

            /**
             * Status do processo
             */
            $table->string('status')->index();

            /**
             * ADMIN
             */
            $table->foreignId('admin_reviewed_by')
                ->nullable()
                ->constrained('users');

            $table->timestamp('admin_reviewed_at')->nullable();
            $table->text('admin_rejection_reason')->nullable();

            /**
             * DIRECTORSHIP
             */
            $table->foreignId('direction_reviewed_by')
                ->nullable()
                ->constrained('users');

            $table->timestamp('direction_reviewed_at')->nullable();
            $table->text('direction_rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};

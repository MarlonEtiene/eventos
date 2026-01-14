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

            /** Identificação do solicitante */
            $table->string('name');       // Nome e sobrenome
            $table->string('sector');     // Setor ou equipe
            $table->string('phone');      // WhatsApp
            $table->string('email');      // E-mail para contato

            /** Tipo de serviço solicitado */
            $table->string('communication_type');
            $table->string('communication_type_other')->nullable();

            /** Solicitação */
            $table->string('title');
            $table->dateTime('start_at'); // Início do evento / ação
            $table->dateTime('end_at');   // Fim do evento / ação

            /** Público-alvo (texto livre, como no modelo) */
            $table->string('target_audience');

            /** Conteúdo da peça */
            $table->text('message');

            /** Prazo de entrega */
            $table->date('delivery_date');

            /** Observações adicionais */
            $table->text('observations')->nullable();

            /** Declaration */
            $table->boolean('declaration')->default(false);

            /** Controle */
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

<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // Identificação
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'function' => ['required', 'string'],
            'sector' => ['required', 'string'],
            'phone' => ['required', 'string'],

            // Decisão
            'has_event' => ['required', 'boolean'],

            // Evento (somente se has_event = true)
            'title' => ['required_if:has_event,true', 'string'],
            'start_at' => ['required_if:has_event,true', 'date'],
            'end_at' => ['required_if:has_event,true', 'date'],
            'local_id' => ['required_if:has_event,true'],
            'target_audience' => ['required_if:has_event,true', 'array'],
            'target_audience.*' => ['exists:audiences,id'],
            'estimated_audience' => ['required_if:has_event,true', 'numeric'],

            'objective' => ['required_if:has_event,true', 'string'],
            'activities' => ['required_if:has_event,true', 'string'],
            'resources' => ['required_if:has_event,true', 'string'],
            'responsibles' => ['required_if:has_event,true', 'string'],

            'with_snack' => ['required_if:has_event,true', 'boolean'],
            'snack_description' => ['required_if:with_snack,true'],

            'with_gift' => ['required_if:with_gift,true', 'boolean'],
            'gift_description' => ['required_if:with_gift,true'],

            'with_contribution' => ['required_if:has_event,true', 'boolean'],
            'contribution_description' => ['required_if:with_contribution,true'],

            // Comunicação
            'communication_type' => ['sometimes'],
            #communication_type_other
            #art_image_text
            'delivery_date' => ['required', 'date'],
            'aware' => ['required_with:communication_type', 'accepted'],
            #attachments
            #declaration
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nome',
            'email' => 'E-Mail',
            'function' => 'Função',
            'sector' => 'Setor',
            'phone' => 'WhatsApp/Telefone',
            'title' => 'Nome do Evento',
            'start_at' => 'Data/Hora de Início',
            'end_at' => 'Data/Hora de Fim',
            'location' => 'Local',
            'target_audience' => 'Público-alvo',
            'estimated_audience' => 'Estimativa de Participantes',
            'objective' => 'Objetivo',
            'activities' => 'Atividades Previstas',
            'resources' => 'Recursos Necessários',
            'responsibles' => 'Responsáveis',
            'with_snack' => 'Haverá Lanche',
            'with_gift' => 'Haverá Brinde',
            'with_contribution' => 'Haverá Solicitação de Recursos',
        ];
    }
}

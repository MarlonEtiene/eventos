<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicantRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'communication_type_id' =>
                $this->communication_type_id === '' ? null : $this->communication_type_id,
        ]);
    }

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
            'title' => ['required_if:has_event,true'],
            'start_at' => ['required_if:has_event,true', 'date'],
            'end_at' => ['required_if:has_event,true', 'date'],
            'local_id' => ['required_if:has_event,true'],
            'target_audience' => ['required_if:has_event,true', 'array'],
            'target_audience.*' => ['exists:audiences,id'],
            'estimated_audience' => ['required_if:has_event,true'],

            'objective' => ['required_if:has_event,true'],
            'activities' => ['required_if:has_event,true'],
            'resources' => ['required_if:has_event,true'],
            'responsibles' => ['required_if:has_event,true'],

            'with_snack' => ['required_if:has_event,true'],
            'snack_description' => ['required_if:with_snack,true'],

            'with_gift' => ['required_if:with_gift,true'],
            'gift_description' => ['required_if:with_gift,true'],

            'with_contribution' => ['required_if:has_event,true'],
            'contribution_description' => ['required_if:with_contribution,true'],

            // Comunicação
            'communication_type_id' => ['nullable', 'integer'],
            #communication_type_other
            #art_image_text
            'delivery_date' => ['required', 'date'],
            'aware' => [
                Rule::when(
                    !is_null($this->communication_type_id)
                    && (int) $this->communication_type_id > 0,
                    ['required', 'accepted'],
                    ['nullable']
                ),
            ],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:10240', // 10MB em KB
            ],
            #declaration
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        $attributes = [
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
            'attachments' => 'Arquivos anexados',
        ];

        if ($this->hasFile('attachments')) {
            foreach ($this->file('attachments') as $index => $file) {
                $attributes["attachments.$index"] = 'Arquivo ' . ($index + 1);
            }
        }

        return $attributes;
    }

    public function messages(): array
    {
        return [
            'attachments.*.mimes' => 'Formato de arquivo não permitido. Tipos aceitos: jpg, jpeg, png, webp, pdf.',
            'attachments.*.max'   => 'O arquivo :attribute excede o tamanho máximo permitido (10MB).',
        ];
    }
}

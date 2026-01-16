<?php

namespace App\Http\Requests;

use App\Models\EventForm;
use App\Models\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_at' => ['required']
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $start = $this->start_at;
            $end   = $this->end_at;

            $conflict = Request::query()
                ->whereIn('status', ['submitted', 'pending_directorship', 'approved'])
                ->where('requestable_type', EventForm::class)
                ->whereHas('requestable', function ($q) use ($start, $end) {
                    $q->where('start_at', '<', $end)
                        ->where('end_at',   '>', $start);
                })
                ->exists();

            if ($conflict) {
                $validator->errors()->add(
                    'start_at',
                    'Já existe um evento agendado nesse período.'
                );
            }
        });
    }
}

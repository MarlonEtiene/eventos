<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CommunicationForm extends Model
{
    protected $fillable = [
        'name',
        'sector',
        'phone',
        'email',
        'communication_type',
        'communication_type_other',
        'title',
        'start_at',
        'end_at',
        'target_audience',
        'message',
        'delivery_date',
        'observations',
        'declaration',
    ];

    protected $casts = [
        'target_audience' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'use_intranet' => 'boolean',
        'use_email' => 'boolean',
        'use_social_media' => 'boolean',
        'use_printed_material' => 'boolean',
        'declaration' => 'boolean',
    ];

    public function request(): MorphOne
    {
        return $this->morphOne(Request::class, 'requestable');
    }
}

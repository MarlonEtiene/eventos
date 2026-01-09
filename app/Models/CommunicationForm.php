<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CommunicationForm extends Model
{
    protected $fillable = [
        'name',
        'function',
        'sector',
        'phone',
        'email',
        'title',
        'communication_type',
        'target_audience',
        'others_audience',
        'start_date',
        'end_date',
        'use_intranet',
        'use_email',
        'use_social_media',
        'use_printed_material',
        'message',
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

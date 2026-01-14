<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Query\Builder as QueryBuilder;
use OwenIt\Auditing\Auditable as Audit;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @mixin EloquentBuilder
 * @mixin QueryBuilder
 */
class EventForm extends Model implements Auditable
{
    use Audit;

    protected $fillable = [
        'name',
        'function',
        'sector',
        'phone',
        'title',
        'start_at',
        'end_at',
        'location',
        'target_audience',
        'others_audience',
        'estimated_audience',
        'description',
        'objective',
        'activities',
        'resources',
        'responsibles',
        'with_snack',
        'snack_description',
        'with_gift',
        'gift_description',
        'with_contribution',
        'contribution_description',
        'declaration',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_start_time' => 'datetime:H:i',
        'event_end_time' => 'datetime:H:i',
        'target_audience' => 'array',
        'with_snack' => 'boolean',
        'with_gift' => 'boolean',
        'with_contribution' => 'boolean',
        'declaration' => 'boolean',
    ];

    public function request(): MorphOne
    {
        return $this->morphOne(Request::class, 'requestable');
    }
}

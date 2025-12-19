<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'event_request_id',
        'event_name',
        'event_date',
        'location',
        'has_food',
        'food_description',
        'has_gift',
        'gift_description',
        'has_external_contribution',
        'external_contribution_description',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(EventRequest::class);
    }
}

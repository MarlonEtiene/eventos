<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Request extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'requester_id',
        'email',
        'name',
        'function',
        'sector',
        'phone',
        'has_event',
        'title',
        'start_at',
        'end_at',
        'local_id',
        'target_audience',
        'others_audience',
        'estimated_audience',
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
        'communication_type_id',
        'communication_type_other',
        'art_image_text',
        'delivery_date',
        'aware',
        'declaration',
        'status',
        'admin_reviewed_by',
        'admin_reviewed_at',
        'admin_rejection_reason',
        'direction_reviewed_by',
        'direction_reviewed_at',
        'direction_rejection_reason',
    ];

    protected $casts = [
        'target_audience' => 'array',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(RequestAttachment::class);
    }

    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class, 'local_id');
    }

    public function commType(): BelongsTo
    {
        return $this->belongsTo(CommunicationType::class);
    }
}

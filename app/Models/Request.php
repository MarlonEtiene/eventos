<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Request extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'requester_id',
        'status',
        'requestable_type',
        'requestable_id',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function requestable()
    {
        return $this->morphTo();
    }

    public function attachments()
    {
        return $this->hasMany(RequestAttachment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
    protected $fillable = [
        'request_id',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];
}

<?php

namespace App\Enuns;

enum RequestStatus
{
    public const SUBMITTED = 'submitted';
    public const REJECTED_BY_ADMIN = 'rejected_by_admin';
    public const PENDING_DIRECTION = 'pending_directorship';
    public const APPROVED = 'approved';
    public const REJECTED_BY_DIRECTION = 'rejected_by_directorship';
}

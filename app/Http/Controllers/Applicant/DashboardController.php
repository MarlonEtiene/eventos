<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\CommunicationForm;
use App\Models\EventForm;
use App\Models\Request;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $requests = Request::where('requester_id', Auth::id())
            ->orderByDesc('created_at')
            ->select([
                'id',
                'status',
                'created_at',
                'requestable_id',
                'requestable_type',
            ])
            ->selectRaw("
                CASE
                    WHEN requestable_type = 'App\\\\Models\\\\EventForm'
                        THEN 'event'
                    ELSE 'communication'
                END AS type
            ")
            ->with([
                'requestable' => function (MorphTo $morphTo) {
                    $morphTo->constrain([
                        EventForm::class => function ($query) {
                            $query->select('id', 'title', 'start_at');
                        },
                        CommunicationForm::class => function ($query) {
                            $query->select('id', 'title', 'start_at');
                        },
                    ]);
                }
            ])
            ->get();

        return Inertia::render('Applicant/Dashboard', [
            'requests' => $requests,
        ]);
    }
}

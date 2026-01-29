<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $requests = Request::query()
            ->where('requester_id', Auth::id())
            ->orderByDesc('created_at')
            ->select([
                'id',
                'status',
                'created_at',
                'has_event',
                'title',
                'start_at',
                'delivery_date',
            ])
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'status' => $request->status,
                    'created_at' => $request->created_at,
                    'type' => $request->has_event ? 'event' : 'communication',
                    'title' => $request->has_event
                        ? $request->title
                        : 'Solicitação de Comunicação',

                    'date' => $request->has_event
                        ? $request->start_at
                        : $request->delivery_date,
                ];
            });
        return Inertia::render('applicant/Dashboard', [
            'requests' => $requests,
        ]);
    }
}

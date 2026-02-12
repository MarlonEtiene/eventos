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
                'name',
                'email',
                'sector',
                'function',
                'status',
                'created_at',
                'has_event',
                'has_communication',
                'title',
                'start_at',
                'delivery_date',
            ])
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'sector' => $request->sector,
                    'function' => $request->function,
                    'status' => $request->status,
                    'created_at' => $request->created_at,
                    'has_event' => $request->has_event,
                    'has_communication' => $request->has_communication,
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

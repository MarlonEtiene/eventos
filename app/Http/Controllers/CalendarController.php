<?php

namespace App\Http\Controllers;

use App\Models\CommunicationForm;
use App\Models\EventForm;
use App\Models\Request;
use Carbon\Carbon;
//use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = Request::query()
            ->whereIn('status', [
                'submitted',
                'pending_directorship',
                'approved',
            ])
            ->whereIn('requestable_type', [
                EventForm::class,
                CommunicationForm::class,
            ])
            ->with('requestable')
            ->get()
            ->map(function (Request $request) {
                $form = $request->requestable;

                if (
                    !$form ||
                    !$form->start_at ||
                    !$form->end_at
                ) {
                    return null;
                }

                $start = Carbon::parse(
                    $form->start_at,
                    'America/Sao_Paulo'
                );

                $end = Carbon::parse(
                    $form->end_at,
                    'America/Sao_Paulo'
                );

                return [
                    'id' => $request->id,
                    'title' => $form->title ?? 'Evento',
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'extendedProps' => [
                        'status' => $request->status,
                        'type' => 'event',
                    ],
                ];
            })
            ->filter()
            ->values();
        return response()->json($events);
    }
}

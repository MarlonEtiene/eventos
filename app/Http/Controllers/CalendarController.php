<?php

namespace App\Http\Controllers;

use App\Models\EventRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = EventRequest::query()
            ->whereIn('status', [
                'submitted',
                'approved_by_admin',
                'approved',
            ])
            ->with('form:id,event_request_id,event_date,event_start_time,event_end_time,event_title')
            ->get()
            ->map(function ($request) {
                $form = $request->form;

                return [
                    'id' => $request->id,
                    'title' => $form->event_title,
                    'start' => Carbon::parse($form->event_date)
                        ->setTimeFromTimeString($form->event_start_time)
                        ->format('Y-m-d H:i:s'),
                    'end' => Carbon::parse($form->event_date)
                        ->setTimeFromTimeString($form->event_end_time)
                        ->format('Y-m-d H:i:s'),
                    'status' => $request->status,
                ];
            });

        return response()->json($events);
    }
}

<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\EventForm;
use App\Models\Request;
use Carbon\Carbon;
use Illuminate\Http\Request as HRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class EventFormController extends Controller
{
    public function create()
    {
        return Inertia::render('event-form');
    }

    /**
     * @throws Throwable
     */
    public function store(HRequest $request)
    {
        $data = $request->all();
        DB::transaction(function () use ($data) {
            $data['event_date_start'] = Carbon::parse($data['event_date_start']);
            $data['event_date_end'] = Carbon::parse($data['event_date_end']);
            $form = EventForm::create($data);

            Request::query()
                ->create([
                    'requester_id' => auth()->id(),
                    'status' => 'submitted',
                    'requestable_type' => EventForm::class,
                    'requestable_id' => $form->id,
                ]);
        });

        return to_route('applicant.dashboard.index');
    }
}

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
    public function create(HRequest $request)
    {
        $date = $request->query('date');
        $start = $request->query('start');

        $startDateTime = null;
        $endDateTime = null;

        if ($date && $start) {
            $startDateTime = Carbon::createFromFormat(
                'Y-m-d H:i',
                "{$date} {$start}",
                'America/Sao_Paulo'
            );

            $endDateTime = $startDateTime->copy()->addHour();
        }

        return Inertia::render('RequestCreate', [
            'prefillStart' => $startDateTime?->format('Y-m-d\TH:i'),
            'prefillEnd' => $endDateTime?->format('Y-m-d\TH:i'),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(HRequest $request)
    {
        $data = $request->all();
        DB::transaction(function () use ($data) {
            $data['start_at'] = Carbon::parse($data['start_at']);
            $data['end_at'] = Carbon::parse($data['end_at']);
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

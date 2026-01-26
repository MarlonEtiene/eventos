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
        $deliveryDate = null;

        if ($date && $start) {
            $startDateTime = Carbon::createFromFormat(
                'Y-m-d H:i',
                "{$date} {$start}",
                'America/Sao_Paulo'
            );

            $endDateTime = $startDateTime->copy()->addHour();
            $deliveryDate = $startDateTime->copy()->addDays(7);
        }

        return Inertia::render('RequestCreate', [
            'prefillStart' => $startDateTime?->format('Y-m-d\TH:i'),
            'prefillEnd' => $endDateTime?->format('Y-m-d\TH:i'),
            'prefillDeliveryDate' => $deliveryDate?->format('Y-m-d'),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(HRequest $request)
    {
        $data = $request->all();
        DB::transaction(function () use ($data, $request) {
            $data['start_at'] = Carbon::parse($data['start_at']);
            $data['end_at'] = Carbon::parse($data['end_at']);
            $data['delivery_date'] = Carbon::parse($data['delivery_date']);
            $form = EventForm::create($data);

            Request::query()
                ->create([
                    'requester_id' => auth()->id(),
                    'status' => 'submitted',
                    'requestable_type' => EventForm::class,
                    'requestable_id' => $form->id,
                ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('requests/'. $process->id, 'public');

                    $process->attachments()->create([
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }
        });

        return to_route('applicant.dashboard.index');
    }
}

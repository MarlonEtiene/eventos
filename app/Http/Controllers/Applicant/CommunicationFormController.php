<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\CommunicationForm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Request as MRequest;
use Throwable;

class CommunicationFormController extends Controller
{
    public function create(Request $request)
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

        return Inertia::render('CommunicationForm', [
            'prefillStart' => $startDateTime?->format('Y-m-d\TH:i'),
            'prefillEnd' => $endDateTime?->format('Y-m-d\TH:i'),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        DB::transaction(function () use ($data, $request) {
            $data['start_at'] = Carbon::parse($data['start_at']);
            $data['end_at'] = Carbon::parse($data['end_at']);
            $form = CommunicationForm::create($data);

            $process = MRequest::query()
                ->create([
                    'requester_id' => auth()->id(),
                    'status' => 'submitted',
                    'requestable_type' => CommunicationForm::class,
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

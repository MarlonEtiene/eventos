<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Applicant\ApplicantRequest;
use App\Models\Audience;
use App\Models\CommunicationType;
use App\Models\Local;
use App\Models\Request;
use Carbon\Carbon;
use Illuminate\Http\Request as HRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class RequestController extends Controller
{
    public function show(Request $request)
    {
        $data = $request->load('attachments', 'local', 'requester');
        $locals = Local::query()
            ->where('active', true)
            ->get(['id', 'name']);

        $audiences = Audience::query()
            ->where('active', true)
            ->get(['id', 'name']);

        $commTypes = CommunicationType::query()
            ->where('active', true)
            ->get(['id', 'name']);

        return Inertia::render('applicant/RequestCreate', [
            'request_data' => $data,
            'read_only' => true,
            'prefillStart' => $data->start_at,
            'prefillEnd' => $data->end_at,
            'prefillDeliveryDate' => $data->delivery_date,
            'locals' => $locals,
            'audiences' => $audiences,
            'comm_types' => $commTypes
        ]);
    }

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

        $locals = Local::query()
            ->where('active', true)
            ->get(['id', 'name']);

        $audiences = Audience::query()
            ->where('active', true)
            ->get(['id', 'name']);

        $commTypes = CommunicationType::query()
            ->where('active', true)
            ->get(['id', 'name']);

        $user = Auth::user();

        return Inertia::render('applicant/RequestCreate', [
            'prefillStart' => $startDateTime?->format('Y-m-d\TH:i'),
            'prefillEnd' => $endDateTime?->format('Y-m-d\TH:i'),
            'prefillDeliveryDate' => $deliveryDate?->format('Y-m-d'),
            'locals' => $locals,
            'audiences' => $audiences,
            'comm_types' => $commTypes,
            'user' => $user
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(ApplicantRequest $request)
    {
        $data = $request->all();
        //dd($data);
        DB::transaction(function () use ($data, $request) {
            $data['start_at'] = Carbon::parse($data['start_at']);
            $data['end_at'] = Carbon::parse($data['end_at']);
            $data['delivery_date'] = Carbon::parse($data['delivery_date']);
            $data['requester_id']= auth()->id();
            $data['status'] = 'sended';
            $req = Request::query()
                ->create($data);

            $user = Auth::user();
            if(!empty($user->name)) {
                $user->name = $data['name'];
                $user->save();
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('requests/'. $req->id, 'public');

                    $req->attachments()->create([
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

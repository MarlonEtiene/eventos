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
        $requests = Request::where('requester_id', Auth::id())
            ->orderByDesc('created_at')
            ->get([
                'id',
                'status',
                'created_at',
            ]);

        return Inertia::render('applicant/Dashboard', [
            'requests' => $requests,
        ]);
    }

    public function newCommunication()
    {
        return Inertia::render('comunication-form');
    }
}

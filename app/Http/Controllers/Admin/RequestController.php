<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audience;
use App\Models\CommunicationType;
use App\Models\Local;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RequestController extends Controller
{
    public function show(Request $request)
    {
        $request->load('attachments', 'commType', 'local');

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

        return Inertia::render('admin/RequestShow', [
            'request_data' => $request,
            'read_only' => true,
            'locals' => $locals,
            'audiences' => $audiences,
            'comm_types' => $commTypes,
            'user' => $user
        ]);
    }
}

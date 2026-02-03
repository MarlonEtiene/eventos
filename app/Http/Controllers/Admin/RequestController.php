<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Inertia\Inertia;

class RequestController extends Controller
{
    public function show(Request $request)
    {
        $request->load('attachments', 'commType', 'local');

        return Inertia::render('admin/RequestShow', [
            'request_data' => $request,
            'read_only' => true,
        ]);
    }
}

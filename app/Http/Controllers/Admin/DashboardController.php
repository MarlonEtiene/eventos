<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request;
use Illuminate\Http\Request as httpRequest;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(httpRequest $httpRequest)
    {
        $query = Request::query()
            ->with(['commType']);

        // 🔹 Filtro por status
        if ($httpRequest->filled('status')) {
            $query->where('status', $httpRequest->status);
        }

        // 🔹 Ordenação
        $sortBy = $httpRequest->get('sort_by', 'created_at');
        $sortDir = $httpRequest->get('sort_dir', 'desc');

        $requests = $query
            ->orderBy($sortBy, $sortDir)
            ->paginate(5)
            ->withQueryString()
            ->through(fn ($r) => [
                'id' => $r->id,
                'title' => $r->title,
                'name' => $r->name,
                'sector' => $r->sector,
                'status' => $r->status,
                'has_event' => $r->has_event,
                'has_communication' => $r->has_communication,
                'created_at' => $r->created_at,
                'comm_type' => $r->commType,
            ]);

        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'sended'   => Request::where('status', 'sended')->count(),
                'approved' => Request::where('status', 'approved')->count(),
                'rejected' => Request::where('status', 'rejected')->count(),
                'total'    => Request::count(),
            ],
            'requests' => $requests,
            'filters' => [
                'status'   => $httpRequest->status,
                'sort_by'  => $sortBy,
                'sort_dir' => $sortDir,
            ],
        ]);
    }

}

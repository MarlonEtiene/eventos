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
        $query = Request::query();

        // 🔹 Filtro por status
        if ($httpRequest->filled('status')) {
            $query->where('status', $httpRequest->status);
        }

        // 🔹 Ordenação
        $sortBy = $httpRequest->get('sort_by', 'created_at');
        $sortDir = $httpRequest->get('sort_dir', 'desc');

        $query->orderBy($sortBy, $sortDir);

        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'sended'   => Request::where('status', 'sended')->count(),
                'approved' => Request::where('status', 'approved')->count(),
                'rejected' => Request::where('status', 'rejected')->count(),
                'total'    => Request::count(),
            ],

            'requests' => $query->get([
                'id',
                'title',
                'name',
                'sector',
                'status',
                'has_event',
                'has_communication',
                'created_at',
            ]),

            // estado atual dos filtros (para o frontend)
            'filters' => [
                'status'   => $httpRequest->status,
                'sort_by'  => $sortBy,
                'sort_dir' => $sortDir,
            ],
        ]);
    }

}

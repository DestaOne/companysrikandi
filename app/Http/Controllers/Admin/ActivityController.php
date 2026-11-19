<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function __construct(private ActivityLogService $activityLogService) {}

    public function index(Request $request): View
    {
        $activities = $this->activityLogService->paginate();

        // Daftar sudah dibuka, jadi lonceng kembali ke nol
        $this->activityLogService->markSeen($request->user());

        return view('admin.activities.index', [
            'activities' => $activities,
        ]);
    }

    /** Dipanggil saat lonceng dibuka: aktivitas sampai saat ini dianggap sudah dilihat. */
    public function seen(Request $request): Response
    {
        $this->activityLogService->markSeen($request->user());

        return response()->noContent();
    }
}

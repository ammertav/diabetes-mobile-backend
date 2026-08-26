<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        return view('audit-trail.index');
    }

    public function loadLogs(Request $request)
    {
        $search = $request->input('search');
        $action = $request->input('action', 'all');
        $page = (int) $request->input('page', 1);

        $query = AuditTrail::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if (!empty($action) && $action !== 'all') {
            $query->where('action', $action);
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        $totalEvents = AuditTrail::count();
        $adminActions = AuditTrail::whereIn('user_role', ['Chief Admin', 'Clinician', 'admin'])->count();
        $patientActions = AuditTrail::where('user_role', 'Patient')->count();
        $securityAlerts = AuditTrail::where('action', 'security_alert')->count();

        $formattedLogs = collect($logs->items())->map(function ($item) {
            return [
                'id' => 'AUD-' . substr($item->id, 0, 4),
                'user_name' => $item->user_name,
                'user_role' => $item->user_role,
                'action' => $item->action,
                'action_label' => $item->action_label,
                'description' => $item->description,
                'ip_address' => $item->ip_address,
                'created_at' => $item->created_at ? $item->created_at->format('d M Y, H:i') : '-',
            ];
        });

        return response()->json([
            'stats' => [
                'total_events' => $totalEvents,
                'admin_actions' => $adminActions,
                'patient_actions' => $patientActions,
                'security_alerts' => $securityAlerts,
            ],
            'logs' => [
                'data' => $formattedLogs,
            ],
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'total' => $logs->total(),
            ]
        ]);
    }
}

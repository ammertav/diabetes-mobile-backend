<?php

namespace App\Http\Controllers;

use App\Actions\Notification\SendFcmNotificationAction;
use App\Models\SafetyAlert;
use App\Models\UserNotification;
use App\Models\AlertNotificationLog;
use App\Utilities\AuditLogger;
use Illuminate\Http\Request;

class SafetyAlertWebController extends Controller
{
    public function index(Request $request)
    {
        return view('safety-alerts.index');
    }

    public function loadLogs(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');
        $page = (int) $request->input('page', 1);

        $query = SafetyAlert::with(['user.mobileProfile', 'fgbRecord']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('user.mobileProfile', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%");
            });
        }

        $alerts = $query->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'stats' => $this->getStats(),
            'alerts' => $this->formatAlerts($alerts->items()),
            'pagination' => [
                'current_page' => $alerts->currentPage(),
                'last_page' => $alerts->lastPage(),
                'total' => $alerts->total(),
            ]
        ]);
    }

    public function notifyUser(Request $request, string $id, SendFcmNotificationAction $sendFcmAction)
    {
        $alert = SafetyAlert::query()->with('user.fcmDevices')->findOrFail($id);
        $user = $alert->user;

        $title = 'Peringatan Darurat Medis';
        $body = 'Gula darah Anda di luar batas aman. Mohon segera ambil tindakan pengamanan.';

        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'safety',
            'title' => $title,
            'body' => $body,
        ]);

        foreach ($user->fcmDevices as $device) {
            $result = $sendFcmAction->execute(
                $device->fcm_token,
                $title,
                $body,
                ['alert_id' => (string) $alert->id, 'type' => 'safety']
            );

            AlertNotificationLog::create([
                'safety_alert_id' => $alert->id,
                'fcm_token' => $device->fcm_token,
                'sent_at' => now(),
                'status' => ($result['success'] ?? false) ? 'success' : 'failed',
            ]);
        }

        AuditLogger::log(
            $request->user(),
            'safety_alert_notify',
            'Manual FCM Notification Sent',
            "Admin sent critical safety notification to patient " . ($user->mobileProfile->name ?? $user->email)
        );

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi darurat berhasil dikirim ke perangkat user.',
        ]);
    }

    private function getStats(): array
    {
        return [
            'total' => SafetyAlert::query()->count('*'),
            'unresolved' => SafetyAlert::query()->whereNull('acknowledged_at', 'and', false)->count('*'),
            'severe' => SafetyAlert::query()->whereIn('type', ['hypo_severe', 'hyper_severe'], 'and', false)->count('*'),
            'resolved' => SafetyAlert::query()->whereNotNull('acknowledged_at', 'and')->count('*'),
        ];
    }

    private function formatAlerts(array $items): array
    {
        return collect($items)->map(function ($item) {
            return [
                'id' => $item->id,
                'patient_name' => $item->user->mobileProfile->name ?? $item->user->email,
                'type' => $item->type,
                'message' => $item->message,
                'glucose_value' => $item->fgbRecord ? (float) $item->fgbRecord->value_mg_dl : null,
                'status' => $item->acknowledged_at ? 'Resolved' : 'Unresolved',
                'action_taken' => $item->action_taken ?? '-',
                'created_at' => $item->created_at ? $item->created_at->format('d M Y, H:i') : '-',
                'resolved_at' => $item->acknowledged_at ? $item->acknowledged_at->format('d M Y, H:i') : '-',
            ];
        })->toArray();
    }
}

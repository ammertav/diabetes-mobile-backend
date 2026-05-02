<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\FastingLogStatus;
use App\Enums\UserProtocolStatus;
use App\Http\Controllers\Controller;
use App\Models\FastingLog;
use App\Models\UserProtocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FastingLogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:completed,skipped,pending',
            'limit' => 'nullable|integer|max:50'
        ]);

        $user = $request->user();

        $baseQuery = FastingLog::whereHas('userProtocol', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        if ($request->start_date) {
            $baseQuery->whereDate('planned_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $baseQuery->whereDate('planned_date', '<=', $request->end_date);
        }

        if ($request->status) {
            $baseQuery->where('status', $request->status);
        }

        // summary
        $total = (clone $baseQuery)->count();
        $completed = (clone $baseQuery)->where('status', FastingLogStatus::COMPLETED)->count();
        $skipped = (clone $baseQuery)->where('status', FastingLogStatus::SKIPPED)->count();

        // cursor pagination
        $query = clone $baseQuery;

        if ($request->cursor) {
            $query->where('id', '<', $request->cursor);
        }

        $limit = min($request->limit ?? 20, 50);

        $logs = $query
            ->orderBy('planned_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit($limit + 1)
            ->get();

        $hasNext = $logs->count() > $limit;
        $logs = $logs->take($limit);
        $nextCursor = $hasNext ? $logs->last()->id : null;

        return response()->json([
            'data' => $logs->map(fn($log) => [
                'id' => $log->id,
                'planned_date' => $log->planned_date,
                'is_completed' => $log->status === FastingLogStatus::COMPLETED,
                'actual_duration_min' => $log->actual_duration_min,
                'mood' => $log->mood,
                'server_timestamp' => now()->toISOString(),
            ]),
            'summary' => [
                'total' => $total,
                'completed' => $completed,
                'skipped' => $skipped,
                'adherence_rate' => $total > 0 ? round($completed / $total, 3) : 0,
            ],
            'pagination' => [
                'has_next' => $hasNext,
                'next_cursor' => $nextCursor,
            ]
        ]);
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'planned_date' => 'required|date|in:' . now()->toDateString(),
            'is_completed' => 'required|boolean',
            'skip_reason' => 'nullable|string|required_if:is_completed,false',
            'mood' => 'nullable|in:good,neutral,bad',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = $request->user();

        $userProtocol = UserProtocol::where('user_id', $user->id)->where('status', '=', UserProtocolStatus::ACTIVE)->firstOrFail();
        $log = FastingLog::where('user_protocol_id', $userProtocol->id)->where('planned_date', '=', $validated['planned_date'])->first();

        if (!$log) {
            return response()->json(['message' => 'No planned fasting'], 404);
        }

        if (!$log->status->isPlanned()) {
            return response()->json([
                'message' => 'Already confirmed',
                "data" => [
                    'id' => $log->id,
                    'planned_date' => $log->planned_date,
                    'is_completed' => $log->status === FastingLogStatus::COMPLETED,
                    'mood' => $log->mood,
                    'notes' => $log->notes,
                    'confirmed_at' => $log->confirmed_at,
                ],
            ], 409);
        }

        $now = now();

        DB::transaction(function () use ($log, $validated, $now) {
            if ($validated['is_completed']) {
                $log->update([
                    'status' => FastingLogStatus::COMPLETED,
                    'started_at' => $now,
                    'mood' => $validated['mood'],
                    'notes' => $validated['notes'],
                    'confirmed_at' => $now,
                ]);
            } else {
                $log->update([
                    'status' => FastingLogStatus::SKIPPED,
                    'skip_reason' => $validated['skip_reason'],
                    'notes' => $validated['notes'],
                    'confirmed_at' => $now,
                ]);
            }
        });

        return response()->json([
            'message' => 'Fasting log confirmed',
            "data" => [
                'id' => $log->id,
                'planned_date' => $log->planned_date,
                'is_completed' => $log->status === FastingLogStatus::COMPLETED,
                'mood' => $log->mood,
                'notes' => $log->notes,
                'confirmed_at' => $log->confirmed_at,
            ],
        ]);
    }

    public function endFasting(int $id)
    {
        $log = FastingLog::findOrFail($id);

        if ($log->status !== FastingLogStatus::COMPLETED) {
            return response()->json(['message' => 'Invalid state'], 400);
        }

        if ($log->ended_at) {
            return response()->json(['message' => 'Already ended'], 409);
        }

        $end = now();

        $duration = $log->started_at
            ? $end->diffInMinutes($log->started_at)
            : null;

        $log->update([
            'ended_at' => $end,
            'actual_duration_min' => $duration,
        ]);

        return response()->json(['message' => 'Fasting ended']);
    }
}

<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\FastingLogStatus;
use App\Http\Controllers\Controller;
use App\Actions\Fasting\ListFastingLogsAction;
use App\Actions\Fasting\ConfirmFastingLogAction;
use App\Actions\Fasting\EndFastingLogAction;
use App\Exceptions\AlreadyConfirmedException;
use Illuminate\Http\Request;

class FastingLogController extends Controller
{
    public function index(Request $request, ListFastingLogsAction $action)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:completed,skipped,pending',
            'limit' => 'nullable|integer|max:50',
            'cursor' => 'nullable|integer',
        ]);

        $result = $action->execute($request->user(), $validated);

        return response()->json([
            'data' => $result['logs']->map(fn($log) => [
                'id' => $log->id,
                'planned_date' => $log->planned_date?->toDateString(),
                'is_completed' => $log->status === FastingLogStatus::COMPLETED,
                'actual_duration_min' => $log->actual_duration_min,
                'mood' => $log->mood,
                'server_timestamp' => now()->toISOString(),
            ]),
            'summary' => $result['summary'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function confirm(Request $request, ConfirmFastingLogAction $action)
    {
        $validated = $request->validate([
            'planned_date' => 'required|date|in:' . now()->toDateString(),
            'is_completed' => 'required|boolean',
            'skip_reason' => 'nullable|string|required_if:is_completed,false',
            'mood' => 'nullable|in:good,neutral,bad',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $log = $action->execute($request->user(), $validated);

            return response()->json([
                'message' => 'Fasting log confirmed',
                "data" => [
                    'id' => $log->id,
                    'planned_date' => $log->planned_date?->toDateString(),
                    'is_completed' => $log->status === FastingLogStatus::COMPLETED,
                    'mood' => $log->mood,
                    'notes' => $log->notes,
                    'confirmed_at' => $log->confirmed_at,
                ],
            ]);
        } catch (AlreadyConfirmedException $e) {
            $log = $e->getLog();
            return response()->json([
                'message' => 'Already confirmed',
                "data" => [
                    'id' => $log->id,
                    'planned_date' => $log->planned_date?->toDateString(),
                    'is_completed' => $log->status === FastingLogStatus::COMPLETED,
                    'mood' => $log->mood,
                    'notes' => $log->notes,
                    'confirmed_at' => $log->confirmed_at,
                ],
            ], 409);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    public function endFasting(string $id, EndFastingLogAction $action)
    {
        try {
            $action->execute($id);

            return response()->json([
                'message' => 'Fasting ended'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}

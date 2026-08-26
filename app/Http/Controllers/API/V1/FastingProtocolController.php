<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\FastingLogStatus;
use App\Enums\UserProtocolStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\FastingProtocolResource;
use App\Models\FastingProtocol;
use App\Models\UserProtocol;
use App\Actions\Fasting\SelectFastingProtocolAction;
use App\Exceptions\SameProtocolActiveException;
use Illuminate\Http\Request;

class FastingProtocolController extends Controller
{
    public function index(Request $request)
    {
        $protocols = FastingProtocol::with('days')->get();

        return FastingProtocolResource::collection($protocols);
    }

    public function selectProtocol(Request $request, SelectFastingProtocolAction $action)
    {
        $validated = $request->validate([
            'protocol_id' => 'required|exists:fasting_protocols,id',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        try {
            $action->execute($request->user(), (int) $validated['protocol_id'], $validated['start_date']);

            return response()->json([
                'message' => 'Protocol selected'
            ]);
        } catch (SameProtocolActiveException $e) {
            $activeProtocol = $e->getActiveProtocol();
            return response()->json([
                'message' => $e->getMessage(),
                'data' => [
                    'protocol_id' => $activeProtocol->fasting_protocol_id,
                    'start_date' => $activeProtocol->start_date,
                    'end_date' => $activeProtocol->end_date,
                    'status' => $activeProtocol->status,
                ]
            ], 422);
        }
    }

    public function active(Request $request)
    {
        $user = $request->user();

        $userProtocol = UserProtocol::with(['protocol', 'logs'])
            ->where('user_id', $user->id)
            ->where('status', UserProtocolStatus::ACTIVE)
            ->firstOrFail();

        $logs = $userProtocol->logs;
        $total = $logs->count();
        $completed = $logs->where('status', FastingLogStatus::COMPLETED)->count();
        $adherence = $total > 0 ? $completed : 0;

        return response()->json([
            'user_protocol_id' => $userProtocol->id,
            'protocol' => $userProtocol->protocol,
            'start_date' => $userProtocol->start_date,
            'status' => $userProtocol->status,
            'adherence_rate' => round($adherence, 2),
        ]);
    }
}

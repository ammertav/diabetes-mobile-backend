<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\FastingLogStatus;
use App\Enums\UserProtocolStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\FastingProtocolResource;
use App\Models\FastingLog;
use App\Models\FastingProtocol;
use App\Models\UserProtocol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FastingProtocolController extends Controller
{
    public function index(Request $request)
    {
        $protocols = FastingProtocol::with('days')->get();

        return FastingProtocolResource::collection($protocols);
    }

    public function selectProtocol(Request $request)
    {
        $validated = $request->validate([
            'protocol_id' => 'required|exists:fasting_protocols,id',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        $user = $request->user();

        $activeProtocol = $user->activeProtocol()->first();

        if ($activeProtocol && $activeProtocol->fasting_protocol_id === $validated['protocol_id']) {
            return response()->json([
                'message' => 'You still have an active same protocol.',
                'data' => [
                    'protocol_id' => $activeProtocol->fasting_protocol_id,
                    'start_date' => $activeProtocol->start_date,
                    'end_date' => $activeProtocol->end_date,
                    'status' => $activeProtocol->status,
                ]
            ], 422);
        }

        // close previous active protocol there is one
        DB::transaction(function () use ($user, $validated) {
            UserProtocol::where('user_id', $user->id)
                ->where('status', '=', UserProtocolStatus::ACTIVE)
                ->update([
                    'status' => UserProtocolStatus::COMPLETED,
                    'end_date' => now()->toDateString(),
                ]);

            $userProtocol = UserProtocol::create([
                'user_id' => $user->id,
                'fasting_protocol_id' => $validated['protocol_id'],
                'start_date' => $validated['start_date'],
                'status' => UserProtocolStatus::ACTIVE,
            ]);

            $protocol = $userProtocol->protocol;

            // generate logs 4 week for planned fasting
            $start = Carbon::parse($validated['start_date']);
            $end = $start->copy()->addWeeks(4);

            $fasting_days = $protocol->days->pluck('day')->values()->toArray();

            for ($date = $start; $date->lte($end); $date->addDay()) {
                if (in_array($date->dayOfWeekIso, $fasting_days)) {
                    FastingLog::create([
                        'user_protocol_id' => $userProtocol->id,
                        'planned_date' => $date->toDateString(),
                        'status' => FastingLogStatus::PLANNED,
                    ]);
                }
            }
        });

        return response()->json([
            'message' => 'Protocol selected'
        ]);
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

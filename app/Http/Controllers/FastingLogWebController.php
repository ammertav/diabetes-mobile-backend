<?php

namespace App\Http\Controllers;

use App\Actions\Fasting\ListAdminFastingLogsAction;
use App\DTO\FastingLogWebFilterData;
use App\Http\Requests\FastingLogWebListRequest;
use App\Http\Resources\FastingLogWebResource;
use Illuminate\Http\Request;

class FastingLogWebController extends Controller
{
    public function index(Request $request)
    {
        return view('fasting-log.index');
    }

    public function loadLogs(FastingLogWebListRequest $request, ListAdminFastingLogsAction $action)
    {
        $validated = $request->validated();
        $page = (int) $request->input('page', 1);

        $dto = FastingLogWebFilterData::fromRequest($validated, $page);
        $result = $action->execute($dto);
        $logs = $result['logs'];

        return response()->json([
            'stats' => $result['stats'],
            'logs' => FastingLogWebResource::collection($logs)->response()->getData(true),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'total' => $logs->total(),
            ]
        ]);
    }
}

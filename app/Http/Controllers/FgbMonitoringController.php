<?php

namespace App\Http\Controllers;

use App\Http\Requests\FgbLogListRequest;
use App\DTO\FgbLogFilterData;
use App\Actions\Fgb\ListFgbLogsAction;
use App\Http\Resources\FgbLogResource;
use Illuminate\Http\Request;

class FgbMonitoringController extends Controller
{
    public function index(Request $request)
    {
        return view('fgb-monitoring.index');
    }

    public function chartData(Request $request, \App\Actions\Fgb\GetFgbChartAction $action)
    {
        $period = $request->input('period', 'daily');
        $group = $request->input('group', 'all');

        $data = $action->execute($period, $group);

        return response()->json($data);
    }

    public function patientDetail($userId, \App\Actions\Fgb\GetPatientFgbDetailAction $action)
    {
        $data = $action->execute($userId);
        return response()->json($data);
    }

    public function loadLogs(FgbLogListRequest $request, ListFgbLogsAction $action)
    {
        $validated = $request->validated();
        $page = (int) $request->input('page', 1);

        $dto = FgbLogFilterData::fromRequest($validated, $page);
        $result = $action->execute($dto);
        $logs = $result['logs'];

        return response()->json([
            'stats' => $result['stats'],
            'logs' => FgbLogResource::collection($logs)->response()->getData(true),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'total' => $logs->total(),
            ]
        ]);
    }

    public function chartDetails(Request $request, \App\Actions\Fgb\GetFgbChartDetailsAction $action)
    {
        $period = $request->input('period', 'daily');
        $group = $request->input('group', 'all');
        $index = (int) $request->input('index', 0);

        $result = $action->execute($period, $group, $index);

        return response()->json([
            'title' => $result['title'],
            'avg_fgb' => $result['avg_fgb'],
            'count' => $result['count'],
            'records' => FgbLogResource::collection($result['records'])->response()->getData(true),
        ]);
    }
}

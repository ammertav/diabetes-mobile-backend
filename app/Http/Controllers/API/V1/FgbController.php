<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FgbStoreRequest;
use App\Models\FgbRecord;
use App\Actions\Fgb\StoreFgbRecordAction;

class FgbController extends Controller
{
    public function index()
    {
        $user = request()->user();

        $records = FgbRecord::query()->where('user_id', $user->id)
            ->orderByDesc('server_timestamp')
            ->get()
            ->map(function ($record) {
                return new \App\Http\Resources\FgbResource($record);
            });

        return response()->json(['data' => $records]);
    }

    public function store(FgbStoreRequest $request, StoreFgbRecordAction $action)
    {
        $result = $action->execute($request->user(), $request->validated());
        $fgb = $result['fgb'];

        return response()->json([
            'data' => [
                'id' => $fgb->id,
                'value_mg_dl' => $fgb->value_mg_dl,
                'context_tag' => $fgb->context_tag,
                'is_fasting_day' => $fgb->is_fasting_day,
                'server_timestamp' => $fgb->server_timestamp,
                'alert' => $result['alert']
            ]
        ], 201);
    }
}

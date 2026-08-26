<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Actions\Fasting\GetStreakSummaryAction;
use App\Http\Resources\StreakResource;
use Illuminate\Http\Request;

class StreakController extends Controller
{
    public function show(Request $request, GetStreakSummaryAction $getStreakSummaryAction)
    {
        $user = $request->user();
        $today = now()->toDateString();

        $streak = $getStreakSummaryAction->execute($user->id, $today);

        return new StreakResource($streak);
    }
}

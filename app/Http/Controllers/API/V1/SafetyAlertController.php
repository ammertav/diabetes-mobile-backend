<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAlertSettingRequest;
use App\Actions\Safety\AcknowledgeSafetyAlertAction;
use App\Actions\Safety\UpdateAlertSettingsAction;
use Illuminate\Http\Request;

class SafetyAlertController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'alert_type' => 'string|nullable|in:' . implode(',', array_column(\App\Enums\AlertType::cases(), 'value')),
            'acknowledged' => 'boolean|nullable',
            'limit' => 'integer|nullable|default:20',
        ]);

        $user = request()->user();

        $alerts = $user->safetyAlerts()
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($alert) {
                return $alert->only(['id', 'type', 'message', 'action', 'created_at']);
            });

        return response()->json(['data' => $alerts]);
    }

    public function acknowledge(Request $request, string $id, AcknowledgeSafetyAlertAction $action)
    {
        $validated = $request->validate([
            'action_taken' => 'nullable|string',
        ]);

        try {
            $alert = $action->execute($request->user(), $id, $validated['action_taken'] ?? null);

            return response()->json([
                'message' => 'Alert acknowledged successfully',
                'data' => $alert,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    public function settings(Request $request)
    {
        $user = $request->user();
        $setting = $user->alertSetting;

        if (!$setting) {
            return response()->json([
                'data' => [
                    'hypo_severe' => \App\Enums\AlertType::HYPO_SEVERE->threshold(),
                    'hypo_mild' => \App\Enums\AlertType::HYPO_MILD->threshold(),
                    'hyper_severe' => \App\Enums\AlertType::HYPER_SEVERE->threshold(),
                    'hyper_mild' => \App\Enums\AlertType::HYPER_MILD->threshold(),
                ]
            ]);
        }

        return response()->json([
            'data' => [
                'hypo_severe' => $setting->hypo_severe,
                'hypo_mild' => $setting->hypo_mild,
                'hyper_severe' => $setting->hyper_severe,
                'hyper_mild' => $setting->hyper_mild,
            ]
        ]);
    }

    public function updateSettings(UpdateAlertSettingRequest $request, UpdateAlertSettingsAction $action)
    {
        $updated = $action->execute($request->user(), $request->validated());

        return response()->json([
            'message' => 'Settings updated successfully',
            'data' => [
                'hypo_severe' => $updated->hypo_severe,
                'hypo_mild' => $updated->hypo_mild,
                'hyper_severe' => $updated->hyper_severe,
                'hyper_mild' => $updated->hyper_mild,
            ]
        ]);
    }
}

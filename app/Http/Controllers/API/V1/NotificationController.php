<?php

namespace App\Http\Controllers\API\V1;

use App\Actions\Notification\GetNotificationHistoryAction;
use App\Actions\Notification\GetNotificationSettingsAction;
use App\Actions\Notification\MarkNotificationAsReadAction;
use App\Actions\Notification\RegisterFcmTokenAction;
use App\Actions\Notification\UpdateNotificationSettingsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Notification\GetNotificationHistoryRequest;
use App\Http\Requests\API\V1\Notification\RegisterFcmTokenRequest;
use App\Http\Requests\API\V1\Notification\UpdateNotificationSettingsRequest;
use App\Http\Resources\NotificationSettingResource;
use App\Http\Resources\UserNotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function settings(Request $request, GetNotificationSettingsAction $action)
    {
        $settings = $action->execute($request->user()->id);
        return new NotificationSettingResource($settings);
    }

    public function updateSettings(
        UpdateNotificationSettingsRequest $request,
        UpdateNotificationSettingsAction $action
    ) {
        $settings = $action->execute($request->user()->id, $request->validated());
        return new NotificationSettingResource($settings);
    }

    public function registerFcmToken(RegisterFcmTokenRequest $request, RegisterFcmTokenAction $action)
    {
        $action->execute($request->user()->id, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'FCM token registered successfully',
        ]);
    }

    public function history(GetNotificationHistoryRequest $request, GetNotificationHistoryAction $action)
    {
        $history = $action->execute($request->user()->id, $request->validated());
        return UserNotificationResource::collection($history);
    }

    public function markAsRead(Request $request, int $id, MarkNotificationAsReadAction $action)
    {
        $notification = $action->execute($request->user()->id, $id);
        return new UserNotificationResource($notification);
    }
}

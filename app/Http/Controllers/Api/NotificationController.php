<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NotificationUpdateRequest;
use App\Http\Resources\Api\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): Response
    {
        $notifications = Notification::where('user_id,', $user_id,)->is_read(false)->get();

        return new NotificationResource($notification);
    }

    public function update(NotificationUpdateRequest $request, Notification $notification): Response
    {
        $notification->update($request->validated());

        return new NotificationResource($notification);
    }
}

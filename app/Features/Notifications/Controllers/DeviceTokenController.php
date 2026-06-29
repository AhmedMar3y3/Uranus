<?php

namespace App\Features\Notifications\Controllers;

use App\Features\Notifications\Requests\DeleteDeviceTokenRequest;
use App\Features\Notifications\Requests\StoreDeviceTokenRequest;
use App\Features\Notifications\Services\DeviceTokenService;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class DeviceTokenController extends ApiController
{
    public function __construct(private readonly DeviceTokenService $deviceTokens)
    {
    }

    public function store(StoreDeviceTokenRequest $request): JsonResponse
    {
        $device = $this->deviceTokens->store($request->user(), $request->validated());

        return $this->successWithDataResponse([
            'id' => $device->id,
            'platform' => $device->platform,
            'device_name' => $device->device_name,
            'last_used_at' => $device->last_used_at,
        ], __('messages.updated_successfully'), null, 'device');
    }

    public function destroy(DeleteDeviceTokenRequest $request): JsonResponse
    {
        $this->deviceTokens->delete($request->user(), $request->validated('fcm_token'));

        return $this->successResponse(__('messages.deleted_successfully'));
    }
}

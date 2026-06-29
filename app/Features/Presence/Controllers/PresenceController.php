<?php

namespace App\Features\Presence\Controllers;

use App\Features\Presence\Services\PresenceService;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class PresenceController extends ApiController
{
    public function __construct(private readonly PresenceService $presence)
    {
    }

    public function online(): JsonResponse
    {
        $user = $this->presence->online(request()->user());

        return $this->successWithDataResponse([
            'online' => $user->is_online,
            'last_seen' => $user->last_seen_at,
        ], __('messages.updated_successfully'), null, 'presence');
    }

    public function offline(): JsonResponse
    {
        $user = $this->presence->offline(request()->user());

        return $this->successWithDataResponse([
            'online' => $user->is_online,
            'last_seen' => $user->last_seen_at,
        ], __('messages.updated_successfully'), null, 'presence');
    }
}

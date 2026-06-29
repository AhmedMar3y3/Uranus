<?php

namespace App\Features\Profile\Controllers;

use App\Features\Profile\Requests\CompleteProfileRequest;
use App\Features\Profile\Services\ProfileService;
use App\Features\Users\Resources\ProfileResource;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class ProfileController extends ApiController
{
    public function __construct(private readonly ProfileService $profiles)
    {
    }

    public function complete(CompleteProfileRequest $request): JsonResponse
    {
        $user = $this->profiles->complete($request->user(), $request->validated());

        return $this->successWithDataResponse(new ProfileResource($user), __('messages.profile_completed_successfully'), null, 'user');
    }
}

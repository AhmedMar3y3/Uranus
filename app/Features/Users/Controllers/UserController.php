<?php

namespace App\Features\Users\Controllers;

use App\Features\Users\Requests\SearchUsersRequest;
use App\Features\Users\Resources\ProfileResource;
use App\Features\Users\Resources\UserSummaryResource;
use App\Features\Users\Services\UserService;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class UserController extends ApiController
{
    public function __construct(private readonly UserService $users)
    {
    }

    public function index(SearchUsersRequest $request): JsonResponse
    {
        $users = $this->users->search(
            $request->user(),
            $request->validated('q'),
            (int) ($request->validated('per_page') ?: 15)
        );

        return $this->successWithDataResponse($users, null, UserSummaryResource::class, 'users');
    }

    public function show(int $user): JsonResponse
    {
        return $this->successWithDataResponse(
            new ProfileResource($this->users->profile(request()->user(), $user)),
            null,
            null,
            'user'
        );
    }
}

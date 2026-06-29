<?php

namespace App\Features\Friends\Controllers;

use App\Enums\StatusCodesEnum;
use App\Features\Friends\Requests\FriendActionRequest;
use App\Features\Friends\Requests\ListFriendsRequest;
use App\Features\Friends\Resources\BlockedUserResource;
use App\Features\Friends\Resources\FriendRequestResource;
use App\Features\Friends\Resources\FriendshipResource;
use App\Features\Friends\Services\FriendshipService;
use App\Features\Users\Resources\UserSummaryResource;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class FriendController extends ApiController
{
    public function __construct(private readonly FriendshipService $friendships)
    {
    }

    public function index(ListFriendsRequest $request): JsonResponse
    {
        $friends = $this->friendships->friends($request->user(), (int) ($request->validated('per_page') ?: 15));

        return $this->successWithDataResponse($friends, null, UserSummaryResource::class, 'friends');
    }

    public function requests(ListFriendsRequest $request): JsonResponse
    {
        $requests = $this->friendships->requests($request->user(), (int) ($request->validated('per_page') ?: 15));

        return $this->resetResponse()
            ->setSuccess(StatusCodesEnum::SUCCESS->value, __('messages.success'))
            ->addResponseField('pagination', [
                'received' => [
                    'i_current_page' => $requests['received']->currentPage(),
                    'i_per_page' => $requests['received']->perPage(),
                    'i_total_pages' => $requests['received']->lastPage(),
                    'i_total_objects' => $requests['received']->total(),
                    'i_items_on_page' => $requests['received']->count(),
                ],
                'sent' => [
                    'i_current_page' => $requests['sent']->currentPage(),
                    'i_per_page' => $requests['sent']->perPage(),
                    'i_total_pages' => $requests['sent']->lastPage(),
                    'i_total_objects' => $requests['sent']->total(),
                    'i_items_on_page' => $requests['sent']->count(),
                ],
            ])
            ->addResponseField('received_requests', FriendRequestResource::collection($requests['received']->items()))
            ->addResponseField('sent_requests', FriendRequestResource::collection($requests['sent']->items()))
            ->response();
    }

    public function blocked(ListFriendsRequest $request): JsonResponse
    {
        $blocked = $this->friendships->blockedUsers($request->user(), (int) ($request->validated('per_page') ?: 15));

        return $this->successWithDataResponse($blocked, null, BlockedUserResource::class, 'blocked_users');
    }

    public function send(FriendActionRequest $request): JsonResponse
    {
        $friendship = $this->friendships->sendRequest($request->user(), User::findOrFail($request->validated('user_id')));

        return $this->successWithDataResponse(new FriendshipResource($friendship->load(['requester', 'addressee'])), __('messages.sent_successfully'), null, 'friendship');
    }

    public function accept(FriendActionRequest $request): JsonResponse
    {
        $friendship = $this->friendships->accept($request->user(), User::findOrFail($request->validated('user_id')));

        return $this->successWithDataResponse(new FriendshipResource($friendship->load(['requester', 'addressee'])), __('messages.updated_successfully'), null, 'friendship');
    }

    public function reject(FriendActionRequest $request): JsonResponse
    {
        $friendship = $this->friendships->reject($request->user(), User::findOrFail($request->validated('user_id')));

        return $this->successWithDataResponse(new FriendshipResource($friendship->load(['requester', 'addressee'])), __('messages.updated_successfully'), null, 'friendship');
    }

    public function cancel(FriendActionRequest $request): JsonResponse
    {
        $this->friendships->cancel($request->user(), User::findOrFail($request->validated('user_id')));

        return $this->successResponse(__('messages.deleted_successfully'));
    }

    public function remove(FriendActionRequest $request): JsonResponse
    {
        $this->friendships->remove($request->user(), User::findOrFail($request->validated('user_id')));

        return $this->successResponse(__('messages.deleted_successfully'));
    }

    public function block(FriendActionRequest $request): JsonResponse
    {
        $friendship = $this->friendships->block($request->user(), User::findOrFail($request->validated('user_id')));

        return $this->successWithDataResponse(new FriendshipResource($friendship->load(['requester', 'addressee'])), __('messages.updated_successfully'), null, 'friendship');
    }

    public function unblock(FriendActionRequest $request): JsonResponse
    {
        $this->friendships->unblock($request->user(), User::findOrFail($request->validated('user_id')));

        return $this->successResponse(__('messages.deleted_successfully'));
    }
}

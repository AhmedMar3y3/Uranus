<?php

namespace App\Features\Chat\Controllers;

use App\Features\Chat\Requests\ListMessagesRequest;
use App\Features\Chat\Requests\StartConversationRequest;
use App\Features\Chat\Resources\ConversationResource;
use App\Features\Chat\Services\ChatService;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ConversationController extends ApiController
{
    public function __construct(private readonly ChatService $chat)
    {
    }

    public function index(ListMessagesRequest $request): JsonResponse
    {
        $conversations = $this->chat->conversations($request->user(), (int) ($request->validated('per_page') ?: 15));

        return $this->successWithDataResponse($conversations, null, ConversationResource::class, 'conversations');
    }

    public function store(StartConversationRequest $request): JsonResponse
    {
        $conversation = $this->chat->startConversation($request->user(), User::findOrFail($request->validated('user_id')));

        return $this->successWithDataResponse(new ConversationResource($conversation), __('messages.added_successfully'), null, 'conversation');
    }
}

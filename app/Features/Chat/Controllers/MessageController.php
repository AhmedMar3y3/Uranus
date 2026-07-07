<?php

namespace App\Features\Chat\Controllers;

use App\Features\Chat\Requests\EditMessageRequest;
use App\Features\Chat\Requests\ListMessagesRequest;
use App\Features\Chat\Requests\SendMessageRequest;
use App\Features\Chat\Requests\TypingRequest;
use App\Features\Chat\Resources\MessageResource;
use App\Features\Chat\Services\ChatService;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class MessageController extends ApiController
{
    public function __construct(private readonly ChatService $chat)
    {
    }

    public function index(ListMessagesRequest $request, int $conversation): JsonResponse
    {
        $messages = $this->chat->messages($request->user(), $conversation, (int) ($request->validated('per_page') ?: 30));

        return $this->successWithDataResponse($messages, null, MessageResource::class, 'messages');
    }

    public function store(SendMessageRequest $request, int $conversation): JsonResponse
    {
        $message = $this->chat->send($request->user(), $conversation, $request->validated());

        return $this->successWithDataResponse(new MessageResource($message), __('messages.sent_successfully'), null, 'message');
    }

    public function update(EditMessageRequest $request, int $conversation, int $message): JsonResponse
    {
        $message = $this->chat->edit($request->user(), $conversation, $message, $request->validated());

        return $this->successWithDataResponse(new MessageResource($message), __('messages.updated_successfully'), null, 'message');
    }

    public function destroy(int $conversation, int $message): JsonResponse
    {
        $this->chat->delete(request()->user(), $conversation, $message);

        return $this->successResponse(__('messages.deleted_successfully'));
    }

    public function delivered(int $conversation, int $message): JsonResponse
    {
        $message = $this->chat->markDelivered(request()->user(), $conversation, $message);

        return $this->successWithDataResponse(new MessageResource($message), __('messages.updated_successfully'), null, 'message');
    }

    public function seen(int $conversation, int $message): JsonResponse
    {
        $message = $this->chat->markSeen(request()->user(), $conversation, $message);

        return $this->successWithDataResponse(new MessageResource($message), __('messages.updated_successfully'), null, 'message');
    }

    public function typing(TypingRequest $request, int $conversation): JsonResponse
    {
        $typing = $this->chat->typing($request->user(), $conversation, (bool) $request->validated('is_typing'));

        return $this->successWithDataResponse($typing->only(['conversation_id', 'user_id', 'is_typing', 'updated_at']), __('messages.updated_successfully'), null, 'typing');
    }
}

<?php

namespace App\Features\Chat\Requests;

use App\Enums\MessageType;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class SendMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(MessageType::class)],
            'body' => ['required_if:type,text', 'nullable', 'string', 'max:5000'],
            'attachment' => ['required_if:type,image,file', 'nullable', 'file', 'max:10240'],
            'reply_to_message_id' => ['nullable', 'integer', 'exists:messages,id'],
        ];
    }
}

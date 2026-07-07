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
            'type' => ['sometimes', Rule::enum(MessageType::class)],
            'ciphertext' => ['required', 'string', 'max:200000'],
            'nonce' => ['required', 'string', 'min:8', 'max:512'],
            'key_id' => ['nullable', 'string', 'max:120'],
            'encryption_version' => ['required', 'string', 'max:50'],
            'attachment' => ['nullable', 'file', 'max:10240'],
            'duration_seconds' => ['nullable', 'integer', 'min:1', 'max:86400'],
            'reply_to_message_id' => ['nullable', 'integer', 'exists:messages,id'],
        ];
    }
}

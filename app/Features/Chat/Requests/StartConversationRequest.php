<?php

namespace App\Features\Chat\Requests;

use App\Http\Requests\BaseRequest;

class StartConversationRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}

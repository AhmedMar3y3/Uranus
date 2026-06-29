<?php

namespace App\Features\Chat\Requests;

use App\Http\Requests\BaseRequest;

class TypingRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'is_typing' => ['required', 'boolean'],
        ];
    }
}

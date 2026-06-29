<?php

namespace App\Features\Friends\Requests;

use App\Http\Requests\BaseRequest;

class FriendActionRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}

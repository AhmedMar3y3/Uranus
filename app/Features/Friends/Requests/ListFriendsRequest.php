<?php

namespace App\Features\Friends\Requests;

use App\Http\Requests\BaseRequest;

class ListFriendsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}

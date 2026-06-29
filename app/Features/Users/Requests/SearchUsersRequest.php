<?php

namespace App\Features\Users\Requests;

use App\Http\Requests\BaseRequest;

class SearchUsersRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}

<?php

namespace App\Features\Chat\Requests;

use App\Http\Requests\BaseRequest;

class ListMessagesRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}

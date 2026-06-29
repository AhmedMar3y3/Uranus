<?php

namespace App\Features\Chat\Requests;

use App\Http\Requests\BaseRequest;

class EditMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:5000'],
        ];
    }
}

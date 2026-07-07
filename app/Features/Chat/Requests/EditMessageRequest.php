<?php

namespace App\Features\Chat\Requests;

use App\Http\Requests\BaseRequest;

class EditMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'ciphertext' => ['required', 'string', 'max:200000'],
            'nonce' => ['required', 'string', 'min:8', 'max:512'],
            'key_id' => ['nullable', 'string', 'max:120'],
            'encryption_version' => ['required', 'string', 'max:50'],
        ];
    }
}

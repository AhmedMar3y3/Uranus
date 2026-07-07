<?php

namespace App\Features\Profile\Requests;

use App\Http\Requests\BaseRequest;

class UpdatePublicKeyRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'public_key' => [
                'required',
                'string',
                'min:32',
                'max:10000',
                'not_regex:/-----BEGIN\s+(RSA\s+|EC\s+|OPENSSH\s+)?PRIVATE\s+KEY-----/i',
            ],
        ];
    }
}

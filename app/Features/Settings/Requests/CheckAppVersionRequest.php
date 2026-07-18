<?php

namespace App\Features\Settings\Requests;

use App\Http\Requests\BaseRequest;

class CheckAppVersionRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'version_code' => ['required', 'integer', 'min:1', 'max:2147483647'],
            'version'      => ['nullable', 'string', 'max:50'],
        ];
    }
}

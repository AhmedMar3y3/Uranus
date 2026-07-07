<?php

namespace App\Features\Profile\Requests;

use App\Enums\Gender;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'username' => [
                'sometimes',
                'string',
                'alpha_dash',
                'min:3',
                'max:30',
                Rule::unique('users', 'username')->ignore($this->user()?->id),
            ],
            'full_name' => ['sometimes', 'nullable', 'string', 'max:120'],
            'gender' => ['sometimes', 'nullable', Rule::enum(Gender::class)],
            'bio' => ['sometimes', 'nullable', 'string', 'max:500'],
            'image' => ['sometimes', 'nullable', 'image', 'max:5120'],
            'public_key' => [
                'sometimes',
                'nullable',
                'string',
                'min:32',
                'max:10000',
                'not_regex:/-----BEGIN\s+(RSA\s+|EC\s+|OPENSSH\s+)?PRIVATE\s+KEY-----/i',
            ],
        ];
    }
}

<?php

namespace App\Features\Profile\Requests;

use App\Enums\Gender;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class CompleteProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'min:3',
                'max:30',
                Rule::unique('users', 'username')->ignore($this->user()?->id),
            ],
            'full_name' => ['nullable', 'string', 'max:120'],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'bio' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }
}

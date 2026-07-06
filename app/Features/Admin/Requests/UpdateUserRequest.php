<?php

namespace App\Features\Admin\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user)],
            'full_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'bio' => ['nullable', 'string', 'max:1000'],
            'completed_profile' => ['nullable', 'boolean'],
            'is_online' => ['nullable', 'boolean'],
        ];
    }
}

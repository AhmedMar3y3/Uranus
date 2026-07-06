<?php

namespace App\Features\Admin\Requests;

use App\Enums\FriendshipStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFriendshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(FriendshipStatus::class)],
            'blocked_by_id' => ['nullable', 'exists:users,id'],
        ];
    }
}

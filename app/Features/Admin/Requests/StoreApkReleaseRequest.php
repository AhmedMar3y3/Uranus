<?php

namespace App\Features\Admin\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreApkReleaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'apk'           => [
                'required',
                'file',
                'max:512000',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (strtolower($value->getClientOriginalExtension()) !== 'apk') {
                        $fail('The uploaded file must be an Android APK.');

                        return;
                    }

                    $signature = file_get_contents($value->getRealPath(), false, null, 0, 2);

                    if ($signature !== 'PK') {
                        $fail('The uploaded file is not a valid Android APK package.');
                    }
                },
            ],
            'version'       => ['nullable', 'string', 'max:100'],
            'release_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

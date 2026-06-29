<?php

namespace App\Http\Requests;

use App\Enums\StatusCodesEnum;
use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        if (request()->is('api/*')) {
            $apiController = new ApiController();
            $firstError = $validator->errors()->first();
            throw new HttpResponseException(
                $apiController->resetResponse()
                    ->setError(StatusCodesEnum::VALIDATION_ERROR->value, $firstError)
                    ->addResponseField('errors', $validator->errors()->toArray())
                    ->setHttpCode(422)
                    ->response()
            );
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}

<?php

namespace App\Features\Settings\Controllers;

use App\Features\Settings\Requests\CheckAppVersionRequest;
use App\Features\Settings\Services\AppVersionService;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AppVersionController extends ApiController
{
    public function __invoke(CheckAppVersionRequest $request, AppVersionService $versions): JsonResponse
    {
        return $this->successWithDataResponse(
            $versions->check(
                $request->integer('version_code'),
                $request->validated('version')
            ),
            keyName: 'app_version'
        );
    }
}

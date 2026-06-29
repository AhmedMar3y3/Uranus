<?php

namespace App\Http\Controllers;

use ReflectionClass;
use ReflectionException;
use App\Enums\StatusCodesEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseStatus;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public array $responseArray  = [];
    public int $responseHttpCode = 200;

    public function addResponseArray(?array $arr = null): ApiController
    {
        if ($arr && count($arr)) {
            $this->responseArray = array_merge($this->responseArray, $arr);
        }

        return $this;
    }

    public function addResponseField(string $name, mixed $value, bool $condition = true): ApiController
    {
        if ($condition) {
            $this->responseArray[$name] = $value;
        }

        return $this;
    }

    public function setHttpCode(?int $http_code = null): ApiController
    {
        $this->responseHttpCode = $http_code ?: 200;

        return $this;
    }

    public function resetResponse(): ApiController
    {
        unset($this->responseArray);
        $this->responseArray = [];

        unset($this->responseHttpCode);
        $this->responseHttpCode = 200;

        return $this;
    }

    public function setStatus(bool $success = true, int $code = 0, string $message_or_key = 'Status Message'): ApiController
    {
        $status = new BaseStatus();

        if (is_bool($success)) {
            $status->success = $success;
        }
        if (is_int($code)) {
            $status->code = $code;
        }
        if (is_string($message_or_key)) {
            $status->message = $message_or_key;
        }

        $this->addResponseArray(['status' => $status]);

        return $this;
    }

    public function setSuccess(int $code = 0, string $message_or_key = 'Status Message'): ApiController
    {
        $message_or_key = __($message_or_key);
        $this->setStatus(true, $code, $message_or_key);

        return $this;
    }

    public function setError(int $code = 0, string $message_or_key = 'Status Message'): ApiController
    {
        $message_or_key = __($message_or_key);
        $this->setStatus(false, $code, $message_or_key);

        return $this;
    }

    public function response(): JsonResponse
    {
        if (! array_key_exists('status', $this->responseArray) && (intval($this->responseHttpCode / 100) == 2)) {
            $routeName      = Route::current()?->getName();
            $message_or_key = $routeName ? $routeName . '.messages.success' : 'messages.success';
            $message        = __($message_or_key);

            $this->setSuccess(StatusCodesEnum::SUCCESS->value, $message);

            $status = $this->responseArray['status'];
            unset($this->responseArray['status']);
            $arr           = [];
            $arr['status'] = $status;
            $arr += $this->responseArray;
            $this->responseArray = $arr;
        }

        return new \App\Http\Responses\JsonResponse($this->responseArray, $this->responseHttpCode);
    }

    public function addResponseResource($data = null, $resourceClassNameOrObject = null, $keyName = null): ApiController
    {
        if ($data instanceof MissingValue) {
            return $this;
        }

        if (! is_array($data) && ! ($data instanceof \Illuminate\Support\Collection)) {
            $data = array_filter([$data]);
        }

        $resourceObject = null;
        if ($resourceClassNameOrObject instanceof JsonResource) {
            $resourceObject = $resourceClassNameOrObject;
        } elseif ($resourceClassNameOrObject && is_string($resourceClassNameOrObject) && class_exists($resourceClassNameOrObject)) {
            $resourceObject = $resourceClassNameOrObject::collection($data);
        }

        if (! $keyName && $resourceClassNameOrObject && is_string($resourceClassNameOrObject) && class_exists($resourceClassNameOrObject)) {
            if (defined("$resourceClassNameOrObject::NAME")) {
                $keyName = $resourceClassNameOrObject::NAME;
            } else {
                $keyName = strtolower(class_basename($resourceClassNameOrObject));
                $keyName = preg_replace('/resource$/', '', $keyName);
            }
        }

        if (! $keyName && $resourceObject) {
            try {
                $reflect = new ReflectionClass($resourceObject);
                if ($reflect && array_key_exists('NAME', $reflect->getConstants())) {
                    $keyName = $reflect->getConstant('NAME');
                }
            } catch (ReflectionException $ex) {
            }
        }

        if ($keyName && $resourceObject) {
            $this->addResponseArray([$keyName => $resourceObject]);
        }

        return $this;
    }

    public function addPagination($paginator): ApiController
    {
        if ($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            $this->addResponseArray([
                'pagination' => [
                    'i_current_page'  => $paginator->currentPage(),
                    'i_per_page'      => $paginator->perPage(),
                    'i_total_pages'   => $paginator->lastPage(),
                    'i_total_objects' => $paginator->total(),
                    'i_items_on_page' => $paginator->count(),
                ],
            ]);
        }

        return $this;
    }

    public function successResponse(string $message = null): JsonResponse
    {
        if ($message === null) {
            $message = __('messages.success');
        }
        return $this->resetResponse()
            ->setSuccess(StatusCodesEnum::SUCCESS->value, $message)
            ->response();
    }

    public function successWithDataResponse($data, string $message = null, $resourceClass = null, string $keyName = null): JsonResponse
    {
        if ($message === null) {
            $message = __('messages.success');
        }

        $response = $this->resetResponse()
            ->setSuccess(StatusCodesEnum::SUCCESS->value, $message);

        if ($data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            $response->addPagination($data);
            $collection = $data->items();
            if ($resourceClass) {
                $response->addResponseResource($collection, $resourceClass, $keyName);
            } else {
                $response->addResponseField($keyName ?: 'data', $collection);
            }
        } elseif ($resourceClass) {
            $response->addResponseResource($data, $resourceClass, $keyName);
        } elseif ($data instanceof JsonResource) {
            $resourceName = $keyName ?: strtolower(class_basename(get_class($data)));
            $resourceName = preg_replace('/resource$/', '', $resourceName);
            $response->addResponseArray([$resourceName => $data]);
        } else {
            $response->addResponseField($keyName ?: 'data', $data);
        }

        return $response->response();
    }

    public function failureResponse(string $message, int $code = null): JsonResponse
    {
        return $this->resetResponse()
            ->setError($code ?? StatusCodesEnum::FAILURE->value, $message)
            ->setHttpCode(400)
            ->response();
    }

    public function unauthenticatedResponse(): JsonResponse
    {
        return $this->resetResponse()
            ->setError(StatusCodesEnum::UNAUTHENTICATED->value, __('messages.unauthenticated'))
            ->setHttpCode(401)
            ->response();
    }

    public function unauthorizedResponse(): JsonResponse
    {
        return $this->resetResponse()
            ->setError(StatusCodesEnum::UNAUTHORIZED->value, __('messages.unauthorized'))
            ->setHttpCode(403)
            ->response();
    }

    public function notFoundResponse(string $message = null): JsonResponse
    {
        $message = $message ?: __('messages.not_found');
        return $this->resetResponse()
            ->setError(StatusCodesEnum::NOT_FOUND->value, $message)
            ->setHttpCode(404)
            ->response();
    }

    public function error(string $message, bool $db_rollback = true, bool $throw_exception = true): JsonResponse
    {
        $message = $message ?: '';
        $message = __($message);

        while ($db_rollback && DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        $code = StatusCodesEnum::FAILURE->value;

        if ($throw_exception) {
            throw new \Exception($message, $code);
        }

        return $this->resetResponse()
            ->setError($code, $message)
            ->setHttpCode(400)
            ->response();
    }
}

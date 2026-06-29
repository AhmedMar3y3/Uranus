<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse as BaseJsonResponse;

class JsonResponse extends BaseJsonResponse
{
    public function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        if (app()->environment('local') && config('app.debug')) {
            $data = $this->addDebugInfo($data);
        }

        parent::__construct($data, $status, $headers, $options);
    }

    protected function addDebugInfo($data): array
    {
        $dataArray = $this->convertToArray($data);
        
        if (! is_array($dataArray)) {
            $dataArray = [];
        }

        $queries = [];
        try {
            $queryLog = DB::getQueryLog();
            foreach ($queryLog as $query) {
                $queries[] = $this->formatQuery($query);
            }
        } catch (\Exception $e) {
        }

        $request     = request();
        $requestData = [
            'method' => $request->method(),
            'url'    => $request->fullUrl(),
            'ip'     => $request->ip(),
        ];

        $allInput = $request->all();
        if (! empty($allInput)) {
            $requestData['input'] = $allInput;
        }

        if ($route = Route::current()) {
            $requestData['route'] = [
                'action' => $route->getActionName(),
            ];
        }

        if (! isset($dataArray['status']) || ! is_array($dataArray['status'])) {
            if (! isset($dataArray['status'])) {
                $dataArray['status'] = [];
            } else {
                $dataArray['status'] = $this->convertToArray($dataArray['status']);
                if (! is_array($dataArray['status'])) {
                    $dataArray['status'] = [];
                }
            }
        }

        $dataArray['status']['debug'] = [
            'queries' => $queries,
            'request' => $requestData,
        ];

        return $dataArray;
    }

    protected function convertToArray($data)
    {
        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->convertToArray($value);
            }
            return $result;
        }

        if (is_object($data)) {
            if ($data instanceof \Illuminate\Http\Resources\Json\JsonResource) {
                try {
                    return $data->toArray(request());
                } catch (\Exception $e) {
                    return json_decode(json_encode($data), true) ?? [];
                }
            }

            if (method_exists($data, 'toArray')) {
                try {
                    return $data->toArray();
                } catch (\Exception $e) {
                    return json_decode(json_encode($data), true) ?? [];
                }
            }

            return json_decode(json_encode($data), true) ?? [];
        }

        return $data;
    }

    protected function formatQuery(array $query): string
    {
        $sql      = $query['query'];
        $bindings = $query['bindings'] ?? [];

        foreach ($bindings as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
            $sql   = preg_replace('/\?/', $value, $sql, 1);
        }

        return $sql;
    }
}

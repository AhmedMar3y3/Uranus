<?php

namespace App\Traits;

trait HttpResponses
{

    public function response($key, $message, $data = [], $statusCode)
    {

        return response()->json([
            'key' => $key,
            'msg' => $message,
            'data' => $data
        ], $statusCode);
    }


    public function successResponse($message = null)
    {
        if ($message === null) {
            $message = __('messages.success');
        }
        return $this->response('success', $message, [], 200);
    }

    public function successWithDataResponse($data, $message = null)
    {
        if ($message === null) {
            $message = __('messages.success');
        }
        return $this->response('success', $message, $data, 200);
    }

    public function unauthenticatedResponse()
    {
        return $this->response('unauthenticated', __('messages.unauthenticated'), [], 401);
    }
    public function unauthorizedResponse()
    {
        return $this->response('unauthorized', __('messages.unauthorized'), [], 403);
    }

    public function failureResponse($message)
    {
        return $this->response('failure', $message, [], 400);
    }
}

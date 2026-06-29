<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseStatus extends JsonResource
{
    public bool $success   = true;
    public int $code       = 0;
    public string $message = '';

    public function __construct($resource = null)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'success' => $this->success,
            'code'    => $this->code,
            'message' => $this->message,
        ];
    }
}

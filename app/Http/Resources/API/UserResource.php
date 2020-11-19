<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $status;
    protected $statusCode;
    protected $message;

    public function __construct($resource, $message = 'success', $statusCode = 200, $status = 1)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->status = $status;
        $this->status_code = $statusCode;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            $this->mergeWhen(false, [
                'first-secret' => 'value',
                'second-secret' => 'value',
            ]),
            'secret' => $this->when(false, 'secret-value'),
        ];
    }

    public function with($request)
    {
        return [
            'status' => $this->status,
            'status_code' => $this->status_code,
            'message' => $this->message
        ];
    }
}

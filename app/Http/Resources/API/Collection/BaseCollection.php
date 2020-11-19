<?php

namespace App\Http\Resources\API\Collection;

use App\Http\Resources\API\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected $status;
    protected $statusCode;
    protected $message;
    protected $model;

    public function __construct($resource, $message = 'success', $statusCode = 200)
    {
        parent::__construct($resource);
        if(get_class($resource) === 'Illuminate\Database\Eloquent\Collection')
            $this->model = 'App\\Http\\Resources\\API\\'.\Str::afterLast(get_class($resource->first()), '\\').'Resource';
        else
            $this->model = 'App\\Http\\Resources\\API\\'.\Str::afterLast(get_class($resource->items()[0]), '\\').'Resource';
        $this->message = $message;
        $this->status = ($statusCode === 200) ? 1 : 0;
        $this->status_code = $statusCode;
    }

    public function toArray($request)
    {
        $collection = new $this->model($request);
        return [
            'data' => $collection->collection($this->collection)
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

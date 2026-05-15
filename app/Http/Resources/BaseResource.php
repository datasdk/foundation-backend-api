<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      
        $resource = $this->resource;

        // Oversæt ressourcen hvis nødvendigt
        $res = $this->translateResource($resource, $request);

        return $res;
    }

 
    /**
     * Process the resource including translation.
     *
     * @param  mixed  $resource
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function translateResource($resource, Request $request)
    {
        if ($request->has("lang") && is_object($resource) && method_exists($resource, "translate")) {
            $resource = $resource->translate($request->get("lang"));
        }

        return $resource;
    }
}

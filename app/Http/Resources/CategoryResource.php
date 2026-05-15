<?php

namespace App\Http\Resources;

use Orion\Http\Resources\Resource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BaseResource;

class CategoryResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */


    public function toArray($req)
    {

        $resource = $this->resource;

        // Process the resource with the parent method.
        $res =  $this->translateResource($resource,$req);

  

        if ($req->has("children")) {

            $type = $req->children;

            $limit = $req->limit ?? null;

            $key = 'categories.' . $type;
        
            // Tjek om bindingen eksisterer
            if (app()->bound($key)) {

                $class = app()->make($key);

                $res[$type] = $this->resource->entries($class)->take($limit)->get()->translate();

            } else {
                // Håndter fejlen, fx returnér en tom array eller en fejlbesked
                $res[$type] = [];
            }

        }
        
        

        return $res;
    }
}

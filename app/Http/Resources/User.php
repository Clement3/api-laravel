<?php

namespace App\Http\Resources;

use App\Http\Resources\Profile as ProfileResource;
use App\Http\Resources\Item as ItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'created_at' => $this->created_at,
            'items' => ItemResource::collection($this->whenLoaded('items')),
            'profile' => new ProfileResource($this->whenLoaded('profile'))
        ];
    }
    
    public function with($request)
    {
        return [
            'version' => config('api.version'),
            'status' => true,
        ];
    }    
}

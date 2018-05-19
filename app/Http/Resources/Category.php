<?php

namespace App\Http\Resources;

use App\Http\Resources\Item as ItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'childrens' => self::collection($this->when(is_null($this->parent_id), $this->whenLoaded('childrens'))),
            'items' => ItemResource::collection($this->whenLoaded('items'))  
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

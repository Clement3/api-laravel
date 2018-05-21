<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Category as CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'parent_category' => new CategoryResource($this->whenLoaded('parentCategory')),
            'child_category' => new CategoryResource($this->whenLoaded('childCategory')),

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

<?php

namespace App\Http\Resources;

use App\Http\Resources\Item as ItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Bookmark extends JsonResource
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
            'created_at' => $this->created_at,
            'item' => new ItemResource($this->whenLoaded('item'))
        ];
    }
}

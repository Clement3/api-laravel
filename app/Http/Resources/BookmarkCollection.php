<?php

namespace App\Http\Resources;

use App\Http\Resources\Item as ItemResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookmarkCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'version' => config('api.version'),
            'status' => true
        ];
    }
}

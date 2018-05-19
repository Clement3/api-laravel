<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
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

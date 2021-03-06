<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

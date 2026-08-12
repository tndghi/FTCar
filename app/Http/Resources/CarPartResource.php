<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarPartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // object_name = tên object trong file .glb, dùng để Three.js map ngược lại
            'object_name' => $this->object_name,
            'label' => $this->label,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/'.$this->image) : null,
            'extra_price' => $this->extra_price ? (float) $this->extra_price : null,
        ];
    }
}
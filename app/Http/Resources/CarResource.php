<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'price' => (float) $this->price,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail ? asset('storage/'.$this->thumbnail) : null,
            'has_3d_model' => (bool) $this->has_3d_model,
            'model_3d_url' => $this->has_3d_model && $this->model_3d_path
                ? asset('storage/'.$this->model_3d_path)
                : null,
            'stock' => $this->stock,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            // chỉ trả về parts khi đã eager-load (tránh N+1 ở trang danh sách)
            'parts' => CarPartResource::collection($this->whenLoaded('parts')),
        ];
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'model' => $this->model ? ucwords(mb_strtolower($this->model)) : null,
            'user_id' => $this->user_id,
            'comfort_category_id' => $this->comfort_category_id,
            'comfort_category' => $this->whenLoaded('comfortCategory', fn () => new ComfortCategoryResource($this->comfortCategory)),
            'user' => $this->whenLoaded('user'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GiftResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'image_url' => !is_null($this->image) ? asset($this->image) : null,
            'reserved_by' => $this->reserved_by,
            'user' => $this->user ?? []
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class GiftResource extends Resource
{
    public function toArray($request)
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

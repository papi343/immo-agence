<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BienImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image_path' => $this->image_path,
            'url' => filter_var($this->image_path, FILTER_VALIDATE_URL) 
                ? $this->image_path 
                : asset('storage/' . $this->image_path),
            'is_primary' => $this->is_primary,
        ];
    }
}

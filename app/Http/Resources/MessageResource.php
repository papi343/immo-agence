<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'tel' => $this->tel,
            'sujet' => $this->sujet,
            'message' => $this->message,
            'bien' => new BienResource($this->whenLoaded('bien')),
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisiteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bien' => new BienResource($this->whenLoaded('bien')),
            'client' => new UserResource($this->whenLoaded('client')),
            'agent' => new UserResource($this->whenLoaded('agent')),
            'date_visite' => $this->date_visite,
            'statut' => $this->statut,
            'commentaire' => $this->commentaire,
            'created_at' => $this->created_at,
        ];
    }
}

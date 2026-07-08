<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BienResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'type' => $this->type,
            'statut' => $this->statut,
            'prix' => $this->prix,
            'surface' => $this->surface,
            'pieces' => $this->pieces,
            'chambres' => $this->chambres,
            'salles_de_bain' => $this->salles_de_bain,
            'adresse' => $this->adresse,
            'ville' => $this->ville,
            'code_postal' => $this->code_postal,
            'features' => $this->features ?? [],
            'agent' => new UserResource($this->whenLoaded('agent')),
            'proprietaire' => new UserResource($this->whenLoaded('proprietaire')),
            'images' => BienImageResource::collection($this->whenLoaded('images')),
            'primary_image' => new BienImageResource($this->whenLoaded('primaryImage')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

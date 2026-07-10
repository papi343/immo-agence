<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bien' => new BienResource($this->whenLoaded('bien')),
            'client' => new UserResource($this->whenLoaded('client')),
            'agent' => new UserResource($this->whenLoaded('agent')),
            'type' => $this->type,
            'prix_final' => $this->prix_final,
            'commission' => $this->commission,
            'date_transaction' => $this->date_transaction,
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'bien_id',
    'client_id',
    'agent_id',
    'date_visite',
    'statut',
    'commentaire'
])]
class Visite extends Model
{
    use HasFactory;

    protected $fillable = [
        'bien_id',
        'client_id',
        'agent_id',
        'date_visite',
        'statut',
        'commentaire'
    ];

    protected function casts(): array
    {
        return [
            'date_visite' => 'datetime',
        ];
    }

    /**
     * Le bien concerné.
     */
    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }

    /**
     * Le client qui effectue la visite.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * L'agent en charge de la visite.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}

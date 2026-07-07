<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'bien_id',
    'client_id',
    'agent_id',
    'type',
    'prix_final',
    'commission',
    'date_transaction'
])]
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bien_id',
        'client_id',
        'agent_id',
        'type',
        'prix_final',
        'commission',
        'date_transaction'
    ];

    protected function casts(): array
    {
        return [
            'date_transaction' => 'date',
            'prix_final' => 'decimal:2',
            'commission' => 'decimal:2',
        ];
    }

    /**
     * Le bien concerné par la transaction.
     */
    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }

    /**
     * Le client impliqué (acheteur ou locataire).
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * L'agent ayant conclu la transaction.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}

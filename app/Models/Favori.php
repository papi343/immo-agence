<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['bien_id', 'client_id'])]
class Favori extends Model
{
    use HasFactory;

    protected $table = 'favoris';

    protected $fillable = [
        'bien_id',
        'client_id'
    ];

    /**
     * Le bien mis en favori.
     */
    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }

    /**
     * Le client qui a mis le bien en favori.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}

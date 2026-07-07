<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'nom',
    'email',
    'tel',
    'sujet',
    'message',
    'bien_id'
])]
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'tel',
        'sujet',
        'message',
        'bien_id'
    ];

    /**
     * Le bien lié au message (s'il y en a un).
     */
    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }
}

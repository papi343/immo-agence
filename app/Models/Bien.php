<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'titre',
    'description',
    'type',
    'statut',
    'prix',
    'surface',
    'pieces',
    'chambres',
    'salles_de_bain',
    'adresse',
    'ville',
    'code_postal',
    'features',
    'agent_id',
    'proprietaire_id'
])]
class Bien extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'type',
        'statut',
        'prix',
        'surface',
        'pieces',
        'chambres',
        'salles_de_bain',
        'adresse',
        'ville',
        'code_postal',
        'features',
        'agent_id',
        'proprietaire_id'
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'prix' => 'decimal:2',
            'surface' => 'decimal:2',
        ];
    }

    /**
     * L'agent qui gère le bien.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Le propriétaire du bien (un client).
     */
    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    /**
     * Les images du bien.
     */
    public function images()
    {
        return $this->hasMany(BienImage::class, 'bien_id');
    }

    /**
     * L'image principale (si présente).
     */
    public function primaryImage()
    {
        return $this->hasOne(BienImage::class, 'bien_id')->where('is_primary', true);
    }

    /**
     * Les visites planifiées pour ce bien.
     */
    public function visites()
    {
        return $this->hasMany(Visite::class, 'bien_id');
    }

    /**
     * Les transactions (ventes ou locations) associées.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'bien_id');
    }

    /**
     * Les utilisateurs ayant mis ce bien en favori.
     */
    public function favoris()
    {
        return $this->hasMany(Favori::class, 'bien_id');
    }
}

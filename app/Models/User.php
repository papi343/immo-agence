<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;



#[Fillable(['nom', 'prenom', 'tel', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
 
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'tel',
        'email',
        'password',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Biens gérés en tant qu'agent.
     */
    public function biensAsAgent()
    {
        return $this->hasMany(Bien::class, 'agent_id');
    }

    /**
     * Biens possédés en tant que propriétaire.
     */
    public function biensAsProprietaire()
    {
        return $this->hasMany(Bien::class, 'proprietaire_id');
    }

    /**
     * Visites planifiées en tant que client.
     */
    public function visitesAsClient()
    {
        return $this->hasMany(Visite::class, 'client_id');
    }

    /**
     * Visites gérées en tant qu'agent.
     */
    public function visitesAsAgent()
    {
        return $this->hasMany(Visite::class, 'agent_id');
    }

    /**
     * Transactions en tant que client (acheteur/locataire).
     */
    public function transactionsAsClient()
    {
        return $this->hasMany(Transaction::class, 'client_id');
    }

    /**
     * Transactions conclues en tant qu'agent.
     */
    public function transactionsAsAgent()
    {
        return $this->hasMany(Transaction::class, 'agent_id');
    }

    /**
     * Les favoris du client.
     */
    public function favoris()
    {
        return $this->hasMany(Favori::class, 'client_id');
    }
}

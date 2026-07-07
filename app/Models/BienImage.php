<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['bien_id', 'image_path', 'is_primary'])]
class BienImage extends Model
{
    use HasFactory;

    protected $table = 'bien_images';

    protected $fillable = [
        'bien_id',
        'image_path',
        'is_primary'
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    /**
     * Le bien associé.
     */
    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }
}

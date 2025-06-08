<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Specie; // Importa el modelo Specie

class Race extends Model
{
    protected $fillable = [
        'species_id',
        'name',
    ];

    public function specie()
    {
        return $this->belongsTo(Specie::class, 'species_id');
    }
}

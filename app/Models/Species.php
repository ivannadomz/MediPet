<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    protected $fillable = [
        'specie',
    ];

   
   public function races()
    {
        return $this->hasMany(Race::class, 'species_id' );
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'species_id');
    }
}

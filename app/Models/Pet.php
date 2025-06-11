<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = [
        'name',
        'birthdate',
        'gender',
        'weight',
        'allergies',
        'species_id',
        'race_id',
        'owner_id',
    ];

    public function specie()
    {
        return $this->belongsTo(Specie::class, 'species_id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function race()
    {
        return $this->belongsTo(Race::class, 'race_id');
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class);
    }
}

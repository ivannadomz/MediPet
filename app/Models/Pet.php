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

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

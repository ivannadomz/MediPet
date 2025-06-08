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
        'race',
        'allergies',
        'species_id',
        'owner_id',
    ];

    public function species()
    {
        return $this->belongsTo(Species::class, 'species_id');
    }

    public function owners()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

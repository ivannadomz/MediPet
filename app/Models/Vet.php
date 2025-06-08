<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vet extends Model
{
    protected $fillable = [
        'user_id',
        'vet_name',
        'phone',
        'birthdate',
        'license_number',
        'speciality',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function appointments()
    {
        return $this->hasMany(appointment::class, 'vet_id');
    }
    //
}

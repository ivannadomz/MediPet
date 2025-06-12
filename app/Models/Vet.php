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

    protected static function booted()
    {
        static::deleting(function ($vet) {
            $vet->user()->delete();
        });
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id'); 
}
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'vet_id');
    }
    //
}

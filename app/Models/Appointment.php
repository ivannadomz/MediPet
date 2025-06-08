<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'vet_id',
        'pet_id',
        'branch_id',
        'appointment_date',
        'status',
        'reason',
    ];

    public function vet()
    {
        return $this->belongsTo(vet::class, 'vet_id');
    }
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'appointment_id');
    }

    //
}

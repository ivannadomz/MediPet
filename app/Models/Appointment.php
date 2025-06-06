<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'vet_id',
        'appointment_date',
        'status',
        'reason',
    ];

    public function vet()
    {
        return $this->belongsTo(vet::class, 'vet_id');
    }
    public function mascota()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    //
}

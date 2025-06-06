<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'specifications',
        'appointment_id',
        'date',
        'reason',
        'diagnosis',
        'treatment',
        'xray_file',
        'lab_file',
    ];

    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}

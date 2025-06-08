<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'schedule',
    ];
    public function appointment()
    {
        return $this->hasMany(Appointment::class, 'branch_id');
    }
    public function products()
    {
        return $this->hasMany(Products::class, 'branch_id');
    }
    //
}

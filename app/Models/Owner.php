<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'address',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

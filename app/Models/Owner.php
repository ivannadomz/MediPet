<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'user_id',
        //'owner_name',
        'phone',
        'address',
    ];

    protected static function booted()
    {
        static::deleting(function ($owner) {
            $owner->user()->delete();
        });
    }
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
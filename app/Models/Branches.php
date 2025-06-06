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
    public function citas()
    {
        return $this->hasMany(Cita::class, 'branch_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'branch_id');
    }
    //
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'category',
        'description',
        'branch_id', 
    ];
    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }
    //
}

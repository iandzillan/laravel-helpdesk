<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }
}

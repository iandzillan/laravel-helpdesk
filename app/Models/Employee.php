<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'image',
        'position_id'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\ticket');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Position');
    }
}

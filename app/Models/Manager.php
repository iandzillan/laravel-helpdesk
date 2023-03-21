<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'position',
        'image',
        'isRequest',
        'department_id'
    ];

    public function user()
    {
        return $this->morphOne('App\Models\User', 'userable');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\ticket');
    }
}

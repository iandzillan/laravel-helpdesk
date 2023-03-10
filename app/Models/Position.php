<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function employees()
    {
        return $this->hasMany('App\Models\Employee');
    }

    public function subDepartment()
    {
        return $this->belongsTo('App\Models\SubDepartment');
    }
}

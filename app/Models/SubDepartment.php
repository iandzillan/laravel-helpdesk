<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function positions()
    {
        return $this->hasMany('App\Models\Position');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function employees()
    {
        return $this->hasManyThrough('App\Models\Employee', 'App\Models\Position');
    }
}

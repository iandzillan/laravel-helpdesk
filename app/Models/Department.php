<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function subDepartments()
    {
        return $this->hasMany('App\Models\SubDepartment');
    }

    public function positions()
    {
        return $this->hasManyThrough('App\Models\Position', 'App\Models\SubDepartment');
    }
}

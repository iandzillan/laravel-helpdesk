<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Urgency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hours'
    ];

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }
}

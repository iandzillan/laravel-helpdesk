<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function urgency()
    {
        return $this->belongsTo('App\Models\Urgency');
    }

    public function trackings()
    {
        return $this->hasMany('App\Models\Tracking');
    }

    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }
}

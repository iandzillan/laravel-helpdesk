<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'subject',
        'urgency_id',
        'user_id',
        'technician_id',
        'sub_category_id',
        'image',
        'description',
        'status',
        'progress'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
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

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
}

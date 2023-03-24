<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'note',
        'status'
    ];

    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket');
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

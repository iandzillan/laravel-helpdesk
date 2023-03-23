<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

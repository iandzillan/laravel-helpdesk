<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = ['ticket_id', 'rating', 'note', 'user_id', 'technician_id'];

    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function getRatingAttribute($value)
    {
        if ($this->attributes['rating'] == null) {
            return '--';
        } else {
            return $value;
        }
    }
}

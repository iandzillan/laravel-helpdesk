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
        'sub_category_id',
        'user_id',
        'technician_id',
        'image',
        'description',
        'status',
        'progress',
        'progress_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function technician()
    {
        return $this->belongsTo('App\Models\User', 'technician_id');
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

    public function getProgressAtAttribute()
    {
        if ($this->attributes['progress_at'] == null) {
            return "--";
        } else {
            return Carbon::parse($this->attributes['progress_at'])->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        }
    }

    public function getStatusAttribute()
    {
        $status = $this->attributes['status'];
        switch ($status) {
            case 1:
                $status = 'Open';
                break;

            case 2:
                $status = 'Approved by Team Leader';
                break;

            case 3:
                $status = 'Approved by Manager';
                break;

            case 4:
                $status = 'On work';
                break;

            case 5:
                $status = 'Pending';
                break;

            case 6:
                $status = 'Close';
                break;

            case 7:
                $status = 'Rejected';
                break;

            default:
                $status = 'Undefined';
                break;
        }

        return $status;
    }
}

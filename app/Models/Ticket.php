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
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function urgency()
    {
        return $this->belongsTo(Urgency::class);
    }

    public function trackings()
    {
        return $this->hasMany(Tracking::class);
    }

    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->tz('Asia/Jakarta')->isoFormat('Y-MM-DD HH:mm:ss');
    }

    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->tz('Asia/Jakarta')->isoFormat('Y-MM-DD HH:mm:ss');
    }

    public function getProgressAtAttribute()
    {
        if ($this->attributes['progress_at'] == null) {
            return "--";
        } else {
            return Carbon::parse($this->attributes['progress_at'])->tz('Asia/Jakarta')->isoFormat('Y-MM-DD HH:mm:ss');
        }
    }

    public function getExpectedFinishAtAttribute()
    {
        if ($this->attributes['expected_finish_at'] == null) {
            return "--";
        } else {
            return Carbon::parse($this->attributes['expected_finish_at'])->tz('Asia/Jakarta')->isoFormat('Y-MM-DD HH:mm:ss');
        }
    }

    public function getFinishAtAttribute()
    {
        if ($this->attributes['finish_at'] == null) {
            return "--";
        } else {
            return Carbon::parse($this->attributes['finish_at'])->tz('Asia/Jakarta')->isoFormat('Y-MM-DD HH:mm:ss');
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
                $status = 'Closed';
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

    public function getDurationAttribute()
    {
        return gmdate('H:i:s', $this->attributes['duration']);
    }
}

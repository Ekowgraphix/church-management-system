<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'attendance_date',
        'member_id',
        'visitor_id',
        'check_in_time',
        'check_out_time',
        'check_in_method',
        'check_in_location',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeForService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }
}

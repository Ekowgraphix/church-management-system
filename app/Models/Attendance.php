<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance_records';

    protected $fillable = [
        'member_id',
        'visitor_id',
        'service_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'check_in_method',
        'check_in_location',
        'notes'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    /**
     * Get the member that owns the attendance.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the visitor associated with the attendance.
     */
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    /**
     * Get the service associated with the attendance.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }
}

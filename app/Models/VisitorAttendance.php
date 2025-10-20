<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorAttendance extends Model
{
    use HasFactory;

    protected $table = 'visitor_attendance';

    protected $fillable = [
        'visitor_id',
        'service_name',
        'attendance_date',
        'check_in_time',
        'check_in_method',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }
}

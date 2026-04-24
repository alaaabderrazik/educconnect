<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_record_id', 'user_id', 'status', 'note'
    ];

    public function record() { return $this->belongsTo(AttendanceRecord::class, 'attendance_record_id'); }
    public function student() { return $this->belongsTo(User::class, 'user_id'); }
}

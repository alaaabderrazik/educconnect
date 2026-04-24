<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'student_class_id', 'session_date', 'session_topic'
    ];

    protected $casts = ['session_date' => 'date'];

    public function professor() { return $this->belongsTo(User::class, 'user_id'); }
    public function studentClass() { return $this->belongsTo(StudentClass::class); }
    public function entries() { return $this->hasMany(AttendanceEntry::class); }
}

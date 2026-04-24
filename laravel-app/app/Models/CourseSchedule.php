<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'student_class_id',
        'subject_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alias for clarity
    public function professor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}

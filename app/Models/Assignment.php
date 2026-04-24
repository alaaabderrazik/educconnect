<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'student_class_id', 'subject_id',
        'title', 'description', 'file_path', 'due_date', 'max_grade'
    ];

    protected $casts = ['due_date' => 'datetime'];

    public function professor() { return $this->belongsTo(User::class, 'user_id'); }
    public function studentClass() { return $this->belongsTo(StudentClass::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function submissions() { return $this->hasMany(Submission::class); }
    
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    protected $fillable = [
        'user_id',
        'subject_id',
        'student_class_id',
        'title',
        'description',
        'file_path',
        'link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class);
    }
}

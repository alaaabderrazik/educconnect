<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    /** @use HasFactory<\Database\Factories\StudentClassFactory> */
    use HasFactory;

    protected $fillable = [
        'class_name',
        'capacity',
        'academic_year_id',
        'level_id',
        'professor_responsible',
        'room',
        'number_of_students'
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function academicLevel()
    {
        return $this->belongsTo(AcademicLevel::class, 'level_id');
    }

    public function responsibleProfessor()
    {
        return $this->belongsTo(User::class, 'professor_responsible');
    }

    // Relationship to student details
    public function studentDetails()
    {
        return $this->hasMany(StudentDetail::class, 'student_class_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'class_id');
    }

}

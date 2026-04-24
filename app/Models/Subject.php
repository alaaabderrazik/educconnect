<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['subject_name', 'code', 'description', 'academic_level_id', 'professor_id', 'hours', 'coefficient'];

    public function academicLevel()
    {
        return $this->belongsTo(AcademicLevel::class);
    }

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function studentClasses()
    {
        return $this->hasMany(StudentClass::class, 'level_id', 'academic_level_id');
    }
}

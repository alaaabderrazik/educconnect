<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'academic_year_id'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function studentClasses()
    {
        return $this->hasMany(StudentClass::class, 'level_id');
    }

    public function studentDetails()
    {
        return $this->hasMany(StudentDetail::class, 'academic_level_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date', 'is_active', 'status'];

    public function academicLevels()
    {
        return $this->hasMany(AcademicLevel::class);
    }

    public function studentClasses()
    {
        return $this->hasMany(StudentClass::class);
    }
}

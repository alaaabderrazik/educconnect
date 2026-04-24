<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessorDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'professor_code',
        'specialty',
        'bio',
        'hire_date',
        'subject_id',
        'office',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id', 'user_id', 'file_path', 'notes',
        'grade', 'feedback', 'submitted_at'
    ];

    protected $casts = ['submitted_at' => 'datetime'];

    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function student() { return $this->belongsTo(User::class, 'user_id'); }
    
    public function isGraded() { return !is_null($this->grade); }
}

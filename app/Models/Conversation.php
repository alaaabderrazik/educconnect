<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'type',
        'name',
        'class_id',
    ];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('last_read_at');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}

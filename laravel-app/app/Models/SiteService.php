<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteService extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'icon', 'image', 'related_course', 'professor_name', 'is_active'];
}

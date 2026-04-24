<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'page_name',
        'section',
        'title',
        'content',
        'image',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}

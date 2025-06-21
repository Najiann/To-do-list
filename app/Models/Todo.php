<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'title', 
        'user_id', 
        'is_done',
        'date',
        'mood',
        'notes',
        'image'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'id',
        'resumo',
        'agenda',
        'status',
        'google_calendar_id',
        'google_calendar_link',
        'user_id',
        'create_at',
        'updated_at',
    ];
}

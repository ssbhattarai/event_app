<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'is_live'
    ];


    public function scopeOrder($q,$column,$order = 'asc')
    {
        return $q->orderBy($column,$order);
    }
}
